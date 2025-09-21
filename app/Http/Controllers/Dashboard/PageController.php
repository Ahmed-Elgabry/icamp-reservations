<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->paginate(20);
        return view('dashboard.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('dashboard.pages.create');
    }

    public function store(StorePageRequest $request)
    {
        $page = Page::create($request->validated());
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => __('dashboard.created_successfully'), 
                'id' => $page->id
            ]);
        }
        
        return redirect()->route('pages.index')->with('success', __('dashboard.created_successfully'));
    }

    public function edit(Page $page)
    {
        return view('dashboard.pages.create', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->update($request->validated());
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => __('dashboard.updated_successfully')
            ]);
        }
        
        return redirect()->route('pages.index')->with('success', __('dashboard.updated_successfully'));
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return response()->json([
            'success' => true, 
            'message' => __('dashboard.deleted_successfully')
        ]);
    }
}


