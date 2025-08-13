<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Violation;
use App\Models\ViolationType;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index()
    {
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
//        dd($request->all());
        $request->validate([
            'violation_type_id' => 'required|exists:violation_types,id',
            'employee_id' => 'required|exists:users,id',
            'employee_justification' => 'nullable|string',
            'action_taken' => 'nullable|required|in:warning,allowance,deduction',
            'deduction_amount' => 'required_if:action_taken,deduction|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        Violation::create([
            'violation_type_id' => $request->violation_type_id,
            'employee_id' => $request->employee_id,
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
            'employee_justification' => 'nullable|string',
            'action_taken' => 'required|in:warning,allowance,deduction',
            'deduction_amount' => 'nullable|required_if:action_taken,deduction|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $violation->update([
            'violation_type_id' => $request->violation_type_id,
            'employee_id' => $request->employee_id,
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
        $violation->delete();
        return redirect()->route('violations.index')
            ->with('success', 'Violation deleted successfully');
    }
}
