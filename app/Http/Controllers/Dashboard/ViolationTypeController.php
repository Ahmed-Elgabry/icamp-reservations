<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use Illuminate\Http\Request;

class ViolationTypeController extends Controller
{
    public function index()
    {
        $types = ViolationType::latest()->get();
        return view('dashboard.violation-types.index', compact('types'));
    }

    public function create()
    {
        return view('dashboard.violation-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        ViolationType::create($request->all());

        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type created successfully');
    }

    public function show(ViolationType $violationType)
    {
        return view('dashboard.violation-types.show', compact('violationType'));
    }

    public function edit(ViolationType $violationType)
    {
        return view('dashboard.violation-types.edit', compact('violationType'));
    }

    public function update(Request $request, ViolationType $violationType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $violationType->update($request->all());

        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type updated successfully');
    }

    public function destroy(ViolationType $violationType)
    {
        $violationType->delete();
        return redirect()->route('violation-types.index')
            ->with('success', 'Violation type deleted successfully');
    }
}
