<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ViolationController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Violation::class);

        $violations = Violation::with(['type', 'employee', 'creator'])
            ->latest()
            ->filter(request()->all())
            ->get();

        $employees = User::all();
        $types = ViolationType::active()->get();

        return view('dashboard.violations.index', compact('violations','employees','types'));
    }

    public function create()
    {
        $types = ViolationType::active()->get();
        $employees = User::all();
        return view('dashboard.violations.create', compact('types', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'violation_type_id' => 'required|exists:violation_types,id',
            'employee_id' => 'required|exists:users,id',
            'violation_date' => 'required|date',
            'violation_time' => 'required',
            'violation_place' => 'required|string|max:255',
            'employee_justification' => 'nullable|string',
            'action_taken' => 'required|in:warning,allowance,deduction',
            'deduction_amount' => 'required_if:action_taken,deduction|numeric|min:0',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('violations/photos');
        }

        Violation::create([
            'violation_type_id' => $request->violation_type_id,
            'employee_id' => $request->employee_id,
            'violation_date' => $request->violation_date,
            'violation_time' => $request->violation_time,
            'violation_place' => $request->violation_place,
            'photo_path' => $photoPath,
            'created_by' => auth()->id(),
            'employee_justification' => $request->employee_justification,
            'action_taken' => $request->action_taken,
            'deduction_amount' => $request->action_taken === 'deduction' ? $request->deduction_amount : null,
            'notes' => $request->notes
        ]);

        return redirect()->route('violations.index')
            ->with('success', 'Violation recorded successfully');
    }

    public function show(Violation $violation)
    {
        return view('dashboard.violations.show', compact('violation'));
    }

    public function edit(Violation $violation)
    {
        $types = ViolationType::active()->get();
        $employees = User::all();
        return view('dashboard.violations.edit', compact('violation', 'types', 'employees'));
    }

    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'violation_type_id' => 'required|exists:violation_types,id',
            'employee_id' => 'required|exists:users,id',
            'violation_date' => 'required|date',
            'violation_time' => 'required',
            'violation_place' => 'required|string|max:255',
            'employee_justification' => 'nullable|string',
            'action_taken' => 'required|in:warning,allowance,deduction',
            'deduction_amount' => 'nullable|required_if:action_taken,deduction|numeric|min:0',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'remove_photo' => 'nullable|boolean'
        ]);

        $photoPath = $violation->photo_path;

        // Handle photo removal
        if ($request->has('remove_photo') && $request->remove_photo) {
            if ($photoPath && Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }
            $photoPath = null;
        }

        // Handle new photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($photoPath && Storage::exists($photoPath)) {
                Storage::delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('violations/photos');
        }

        $violation->update([
            'violation_type_id' => $request->violation_type_id,
            'employee_id' => $request->employee_id,
            'violation_date' => $request->violation_date,
            'violation_time' => $request->violation_time,
            'violation_place' => $request->violation_place,
            'photo_path' => $photoPath,
            'employee_justification' => $request->employee_justification,
            'action_taken' => $request->action_taken,
            'deduction_amount' => $request->action_taken === 'deduction' ? $request->deduction_amount : null,
            'notes' => $request->notes
        ]);

        return redirect()->route('violations.index')
            ->with('success', 'Violation updated successfully');
    }

    public function destroy(Violation $violation)
    {
        // Delete photo if exists
        if ($violation->photo_path && Storage::exists($violation->photo_path)) {
            Storage::delete($violation->photo_path);
        }

        $violation->delete();
        return redirect()->route('violations.index')
            ->with('success', 'Violation deleted successfully');
    }
}
