<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\MeetingAttendee;
use App\Models\MeetingTopic;
use App\Models\Task;
use App\Models\User;
use App\Models\OrderRate;
//use App\Notifications\MeetingCreatedNotification;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with(['creator', 'attendees', 'topics'])
            ->latest()
            ->get();

        return view('dashboard.meetings.index', compact('meetings'));
    }

    public function create()
    {
        $users = User::all();
        $topics = OrderRate::pluck('review', 'id');
        return view('dashboard.meetings.create', compact('users', 'topics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'attendees' => 'required|array',
            'attendees.*' => 'required|exists:users,id',
            'topics' => 'nullable|array',
            'topics.*.topic' => 'required|string|max:255',
            'topics.*.discussion' => 'required|string',
            'topics.*.action_items' => 'nullable|string',
            'topics.*.assigned_to' => 'nullable|exists:users,id',
        ]);

        $meeting = Meeting::create([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
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
                    'action_items' => $topic['action_items'],
                    'assigned_to' => $topic['assigned_to'] ?? null,
                ]);

                if ($topic['assigned_to']) {
                    $task = Task::create([
                        'title' => $topic['topic'],
                        'description' => $topic['action_items'],
                        'assigned_to' => $topic['assigned_to'],
                        'due_date' => $meeting->date,
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
        return view('dashboard.meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $users = User::all();
        $topics = OrderRate::pluck('review', 'id');
        return view('dashboard.meetings.create', compact('meeting', 'users', 'topics'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'attendees' => 'required|array',
            'attendees.*' => 'required|exists:users,id',
            'topics' => 'nullable|array',
            'topics.*.topic' => 'required|string|max:255',
            'topics.*.discussion' => 'required|string',
            'topics.*.action_items' => 'nullable|string',
            'topics.*.assigned_to' => 'nullable|exists:users,id',
        ]);

        // Update meeting data
        $meeting->update([
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'location' => $validated['location'],
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
                    'action_items' => $topic['action_items'],
                    'assigned_to' => $topic['assigned_to'] ?? null,
                ]);

                if (!empty($topic['assigned_to'])) {
                    $task = Task::create([
                        'title' => $topic['topic'],
                        'description' => $topic['action_items'],
                        'assigned_to' => $topic['assigned_to'],
                        'due_date' => $meeting->date,
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
        $meeting->delete();
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully');
    }
}
