<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\NoticeType;
use Illuminate\Http\Request;

class NoticeTypeController extends Controller
{
    public function index()
    {
        $noticeTypes = NoticeType::latest()->paginate(10);
        return view('dashboard.notice_types.index', compact('noticeTypes'));
    }

    public function create()
    {
        return view('dashboard.notice_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        NoticeType::create([
            'name' => $request->name,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('notice-types.index')
            ->with('success', __('dashboard.notice_type_created_successfully'));
    }

    public function edit(NoticeType $noticeType)
    {
        return view('dashboard.notice_types.create', compact('noticeType'));
    }

    public function update(Request $request, NoticeType $noticeType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $noticeType->update([
            'name' => $request->name,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('notice-types.index')
            ->with('success', __('dashboard.notice_type_updated_successfully'));
    }

    public function destroy(NoticeType $noticeType)
    {
        $noticeType->delete();
        return redirect()->route('notice-types.index')
            ->with('success', __('dashboard.notice_type_deleted_successfully'));
    }
}
