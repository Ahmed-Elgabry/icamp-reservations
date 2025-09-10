<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\TaskType;
use Illuminate\Http\Request;

class TaskTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taskTypes = TaskType::latest()->get();
        return view('dashboard.task_types.index', compact('taskTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.task_types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        TaskType::create($validated);

        return redirect()
            ->route('task-types.index')
            ->with('success', __('dashboard.task_type_created_successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  TaskType  $taskType
     * @return \Illuminate\Http\Response
     */
    public function show(TaskType $taskType)
    {
        $taskType->load('tasks.assignedUser');
        return view('dashboard.task_types.show', compact('taskType'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  TaskType  $taskType
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskType $taskType)
    {
        return view('dashboard.task_types.edit', compact('taskType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  TaskType  $taskType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TaskType $taskType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:task_types,name,' . $taskType->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $taskType->update($validated);

        return redirect()
            ->route('task-types.index')
            ->with('success', __('dashboard.task_type_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  TaskType  $taskType
     * @return \Illuminate\Http\Response
     */
    public function destroy(TaskType $taskType)
    {
        // Check if task type has associated tasks
        if ($taskType->tasks()->count() > 0) {
            return redirect()
                ->route('task-types.index')
                ->with('error', __('dashboard.cannot_delete_task_type_with_tasks'));
        }

        $taskType->delete();

        return redirect()
            ->route('task-types.index')
            ->with('success', __('dashboard.task_type_deleted_successfully'));
    }
}
