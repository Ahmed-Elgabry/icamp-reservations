<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\MeetingLocation;
use Illuminate\Http\Request;

class MeetingLocationController extends Controller
{
    public function index()
    {
        $locations = MeetingLocation::latest()->get();
        return view('dashboard.meetings.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('dashboard.meetings.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        MeetingLocation::create($validated);

        return redirect()->route('meeting-locations.index')
            ->with('success', 'Meeting location created successfully');
    }

    public function show(MeetingLocation $meetingLocation)
    {
        return view('dashboard.meetings.locations.show', compact('meetingLocation'));
    }

    public function edit(MeetingLocation $meetingLocation)
    {
        return view('dashboard.meetings.locations.edit', compact('meetingLocation'));
    }

    public function update(Request $request, MeetingLocation $meetingLocation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'is_active' => 'boolean'
        ]);

        $meetingLocation->update($validated);

        return redirect()->route('meeting-locations.index')
            ->with('success', 'Meeting location updated successfully');
    }

    public function destroy(MeetingLocation $meetingLocation)
    {
        $meetingLocation->delete();
        return redirect()->route('meeting-locations.index')
            ->with('success', 'Meeting location deleted successfully');
    }
}
