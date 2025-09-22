<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\InternalNote;
use Illuminate\Http\Request;

class InternalNoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $notes = InternalNote::orderBy('created_at', 'desc')->get();
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['data' => $notes]);
        }
        return view('dashboard.internal_notes.index', compact('notes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'note_name' => 'nullable|string|max:255',
            'note_content' => 'nullable|string',
        ]);

        $note = InternalNote::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $note]);
        }

        return redirect()->back()->with('success', __('dashboard.create_item'));
    }
    public function edit(Request $request, InternalNote $internalNote)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['note' => $internalNote]);
        }
        return view('dashboard.internal_notes.index', compact('internalNote'));
    }
    public function update(Request $request, InternalNote $internalNote)
    {
        $data = $request->validate([
            'note_name' => 'required|string|max:255',
            'note_content' => 'required|string',
        ]);

        $internalNote->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'data' => $internalNote]);
        }

        return redirect()->route('dashboard.internal_notes.index')->with('success', __('dashboard.update_item'));
    }

    public function destroy(Request $request, InternalNote $internalNote)
    {
        $internalNote->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        return redirect()->back()->with('success', __('dashboard.delete'));
    }
}
