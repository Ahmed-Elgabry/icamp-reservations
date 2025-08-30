<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\TasksExport;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskType;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Repositories\IUserRepository;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    private $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->authorize('viewAny', Task::class);
        $tasks = Task::with(['assignedUser', 'creator', 'taskType'])->latest()->get();
        return view('dashboard.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $this->authorize('create', Task::class);
        $users = $this->userRepository->getAll();
        $taskTypes = TaskType::active()->get();
        return view('dashboard.tasks.create', compact('users', 'taskTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'task_type_id' => 'nullable|exists:task_types,id',
        ]);

        $validated['created_by'] = auth()->id();

        $task = Task::create($validated);

        Notification::send($task->assignedUser, new TaskAssignedNotification($task));

        return redirect()
            ->route('tasks.index')
            ->with('success', __('dashboard.task_created_successfully'));
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $users = $this->userRepository->getAll();
        $taskTypes = TaskType::active()->get();
        return view('dashboard.tasks.create', compact('task', 'users', 'taskTypes'));
    }

    public function update(Request $request, Task $task)
    {
        // Authorization check
        $this->authorize('update', $task);

        // Base validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed,failed',
            'task_type_id' => 'nullable|exists:task_types,id',
        ];

        // Conditional validation
        if ($request->status == 'failed') {
            $rules['failure_reason'] = 'required|string|min:3|max:500';
        }

        $validated = $request->validate($rules);

        // Get the original assigned_to before update
        $originalAssignee = $task->assigned_to;

        DB::transaction(function () use ($task, $validated, $originalAssignee) {
            // Update task
            $task->update($validated); // Handle reassignment notifications
            if ($task->wasChanged('assigned_to')) {
                // Delete notifications for old assignee
                if ($originalAssignee) {
                    $originalUser = User::find($originalAssignee);
                    if ($originalUser) {
                        $originalUser->notifications()
                            ->where('type', TaskAssignedNotification::class)
                            ->where('data->task_id', $task->id)
                            ->delete();
                    }
                }

                // Send notification to new assignee
                Notification::send($task->assignedUser, new TaskAssignedNotification($task));
            }
        });

        // Handle different response types
        if ($request->wantsJson()) {
            return response()->json([
                'message' => __('dashboard.task_updated_successfully'),
                'task' => $task
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', __('dashboard.task_updated_successfully'));
    }

    public function destroy(Task $task)
    {
        // Delete attachments
        $attachments = ['audio', 'video', 'photo'];
        foreach ($attachments as $type) {
            if ($task->{"{$type}_attachment"}) {
                Storage::delete($task->{"{$type}_attachment"});
            }
        }

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', __('dashboard.task_deleted_successfully'));
    }

    public function myTasks()
    {
        $tasks = Task::where('assigned_to', auth()->id())
            ->with('creator', 'notifications', 'taskType')
            ->latest()
            ->get();

        return view('dashboard.tasks.my_tasks', compact('tasks'));
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        //        dd($request->audio_attachment);
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,failed',
            'failure_reason' => 'required_if:status,failed|nullable|string',
            'photo_attachment' => 'nullable|image|max:1048',
            'video_attachment' => 'nullable|file|max:1048',
            'audio_attachment' => 'nullable|file|max:1048',
            'delete_photo' => 'nullable|boolean',
            'delete_video' => 'nullable|boolean',
            'delete_audio' => 'nullable|boolean'
        ]);

        // Handle file deletions
        if ($request->delete_photo && $task->photo_attachment) {
            Storage::delete($task->photo_attachment);
            $validated['photo_attachment'] = null;
        }

        if ($request->delete_video && $task->video_attachment) {
            Storage::delete($task->video_attachment);
            $validated['video_attachment'] = null;
        }

        if ($request->delete_audio && $task->audio_attachment) {
            Storage::delete($task->audio_attachment);
            $validated['audio_attachment'] = null;
        }

        // Handle file uploads
        if ($request->hasFile('photo_attachment')) {
            $validated['photo_attachment'] = $request->file('photo_attachment')->store('task_attachments/photos');
        }

        if ($request->hasFile('video_attachment')) {
            $validated['video_attachment'] = $request->file('video_attachment')->store('task_attachments/videos');
        }

        if ($request->hasFile('audio_attachment')) {
            $validated['audio_attachment'] = $request->file('audio_attachment')->store('task_attachments/audio');
        }

        $task->update($validated);

        return response()->json(['success' => true]);
    }

    public function reports()
    {
        //        $this->authorize('tasks.reports');

        $filters = [
            'status' => request('status'),
            'employee_id' => request('employee_id'),
            'date_from' => request('date_from'),
            'date_to' => request('date_to')
        ];

        $query = Task::query()->with(['assignedUser', 'creator', 'taskType']);

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        if ($filters['employee_id']) {
            $query->where('assigned_to', $filters['employee_id']);
        }

        if ($filters['date_from']) {
            $query->whereDate('due_date', '>=', $filters['date_from']);
        }

        if ($filters['date_to']) {
            $query->whereDate('due_date', '<=', $filters['date_to']);
        }

        $tasks = $query->latest()->get();
        $employees = User::get();

        $completionStats = [
            'total' => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'failed' => Task::where('status', 'failed')->count()
        ];

        return view('dashboard.tasks.reports', compact('tasks', 'employees', 'filters', 'completionStats'));
    }

    public function exportReports()
    {
        //        $this->authorize('tasks.reports');

        return Excel::download(new TasksExport(
            request('status'),
            request('employee_id'),
            request('date_from'),
            request('date_to')
        ), 'tasks-report.xlsx');
    }
}
