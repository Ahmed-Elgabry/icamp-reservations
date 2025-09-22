<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use App\Models\MeetingLocation;
use App\Models\MeetingTopic;
use App\Models\Task;
use App\Models\User;
use App\Models\OrderRate;
//use App\Notifications\MeetingCreatedNotification;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class MeetingController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Meeting::class);

        $meetings = Meeting::with(['creator', 'attendees', 'topics'])
            ->latest()
            ->get();

        return view('dashboard.meetings.index', compact('meetings'));
    }

    public function create()
    {
        $this->authorize('create', Meeting::class);

        $users = User::all();
        $topics = OrderRate::pluck('review', 'id');
        $locations = MeetingLocation::where('is_active', true)->get();
        return view('dashboard.meetings.create', compact('users', 'topics', 'locations'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Meeting::class);

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location_id' => 'required|exists:meeting_locations,id',
            'notes' => 'nullable|string',
            'attendees' => 'required|array',
            'attendees.*' => 'required|exists:users,id',
            'topics' => 'nullable|array',
            'topics.*.topic' => 'required|string|max:255',
            'topics.*.discussion' => 'required|string',
            'topics.*.action_items' => 'nullable|string',
            'topics.*.assigned_to' => 'nullable|exists:users,id',
            'topics.*.due_date' => 'nullable|date|after_or_equal:today',
        ]);

        $meeting = Meeting::create([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location_id' => $validated['location_id'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Add attendees
        foreach ($validated['attendees'] as $userId) {
            MeetingAttendee::create([
                'meeting_id' => $meeting->id,
                'user_id' => $userId,
                'job_title' => User::find($userId)->job ?? null,
            ]);
        }


        // Add topics and create tasks if assigned
        if (isset($validated['topics'])) {
            foreach ($validated['topics'] as $topic) {
                $meetingTopic = MeetingTopic::create([
                    'meeting_id' => $meeting->id,
                    'topic' => $topic['topic'],
                    'discussion' => $topic['discussion'],
                    'action_items' => $topic['action_items'] ?? null,
                    'assigned_to' => $topic['assigned_to'] ?? null,
                    'due_date' => $topic['due_date'] ?? null,
                ]);

                if ($topic['assigned_to']) {
                    $task = Task::create([
                        'title' => $topic['topic'],
                        'description' => $topic['discussion'],
                        'assigned_to' => $topic['assigned_to'],
                        'due_date' => $topic['due_date'] ?? $meeting->date,
                        'priority' => 'medium',
                        'created_by' => auth()->id(),
                    ]);

                    $meetingTopic->update(['task_id' => $task->id]);

                    // Send notification
                    Notification::send($task->assignedUser, new TaskAssignedNotification($task));
                }
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting created successfully');
    }

    public function show(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        return view('dashboard.meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $users = User::all();
        $topics = OrderRate::pluck('review', 'id');
        $locations = MeetingLocation::where('is_active', true)->get();
        return view('dashboard.meetings.create', compact('meeting', 'users', 'topics', 'locations'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $this->authorize('update', $meeting);

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location_id' => 'required|exists:meeting_locations,id',
            'notes' => 'nullable|string',
            'attendees' => 'required|array',
            'attendees.*' => 'required|exists:users,id',
            'topics' => 'nullable|array',
            'topics.*.topic' => 'required|string|max:255',
            'topics.*.discussion' => 'required|string',
            'topics.*.action_items' => 'nullable|string',
            'topics.*.assigned_to' => 'nullable|exists:users,id',
            'topics.*.due_date' => 'nullable|date|after_or_equal:today',
        ]);

        // Update meeting data
        $meeting->update([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location_id' => $validated['location_id'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // Clear old attendees & re-insert
        $meeting->attendees()->delete();
        foreach ($validated['attendees'] as $userId) {
            MeetingAttendee::create([
                'meeting_id' => $meeting->id,
                'user_id' => $userId,
                'job_title' => User::find($userId)->job ?? null,
            ]);
        }

        // Clear old topics & tasks
        foreach ($meeting->topics as $oldTopic) {
            if ($oldTopic->task) {
                $oldTopic->update(['task_id' => null]);
                $oldTopic->task->delete();
            }
            $oldTopic->delete();
        }


        // Add new topics & tasks if assigned
        if (isset($validated['topics'])) {
            foreach ($validated['topics'] as $topic) {
                $meetingTopic = MeetingTopic::create([
                    'meeting_id' => $meeting->id,
                    'topic' => $topic['topic'],
                    'discussion' => $topic['discussion'],
                    'action_items' => $topic['action_items'] ?? null,
                    'assigned_to' => $topic['assigned_to'] ?? null,
                    'due_date' => $topic['due_date'] ?? null,
                ]);

                if (!empty($topic['assigned_to'])) {
                    $task = Task::create([
                        'title' => $topic['topic'],
                        'description' => $topic['discussion'],
                        'assigned_to' => $topic['assigned_to'],
                        'due_date' => $topic['due_date'] ?? $meeting->date,
                        'priority' => 'medium',
                        'created_by' => auth()->id(),
                    ]);

                    $meetingTopic->update(['task_id' => $task->id]);

                    // Send notification
                    Notification::send($task->assignedUser, new TaskAssignedNotification($task));
                }
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully');
    }

    public function destroy(Meeting $meeting)
    {
        $this->authorize('delete', $meeting);

        $meeting->delete();
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully');
    }

    public function exportPdf()
    {
        $meetings = Meeting::with(['creator', 'attendees.user', 'topics.assignee', 'location'])
            ->latest()
            ->get();

        $html = view('dashboard.meetings.export', compact('meetings'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            // Add RTL support for Arabic
            'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            'default_font' => 'DejaVuSans'
        ]);

        // Enable image processing
        $mpdf->showImageErrors = true;

        $mpdf->WriteHTML($html);

        $filename = __('dashboard.meetings') . "-" . date('Y-m-d') . ".pdf";
        return $mpdf->Output($filename, 'D');
    }

    public function exportSinglePdf(Meeting $meeting)
    {
        $this->authorize('view', $meeting);

        $meeting->load(['creator', 'attendees.user', 'topics.assignee', 'location']);
        $meetings = collect([$meeting]); // Wrap single meeting in collection for existing view

        $html = view('dashboard.meetings.export', compact('meetings'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            // Add RTL support for Arabic
            'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            'default_font' => 'DejaVuSans'
        ]);

        // Enable image processing
        $mpdf->showImageErrors = true;

        $mpdf->WriteHTML($html);

        $filename = __('dashboard.meeting') . "-{$meeting->meeting_number}-" . date('Y-m-d') . ".pdf";
        return $mpdf->Output($filename, 'D');
    }
}
