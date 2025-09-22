<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceSiteAndCustomerService;

class ServiceSiteAndCustomerServiceController extends Controller
{

    public function create()
    {
        $items = ServiceSiteAndCustomerService::all();
        return view('dashboard.service_site_customer_service.create', compact('items'));
    }
    public function edit($id)
    {
        $item = ServiceSiteAndCustomerService::findOrFail($id);
        return view('dashboard.service_site_customer_service.create', compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'serviceSite' => 'required|string',
            'workername_en' => 'required|string',
            'workername_ar' => 'required|string',
            'workerphone' => 'required|string',
        ]);

        ServiceSiteAndCustomerService::create([
            'service_site' => $validatedData['serviceSite'],
            'workername_en' => $validatedData['workername_en'],
            'workername_ar' => $validatedData['workername_ar'],
            'workerphone' => $validatedData['workerphone'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('item_added_successfully'),

                'data' => $validatedData,
            ], 201);
        }

        return redirect()->route('service_site_customer_service.create')->with('success',  __('item_added_successfully'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'serviceSite' => 'required|string',
            'workername_en' => 'required|string',
            'workername_ar' => 'required|string',
            'workerphone' => 'required|string',
        ]);

        $item = ServiceSiteAndCustomerService::findOrFail($id);
        $item->update([
            'service_site' => $validatedData['serviceSite'],
            'workername_en' => $validatedData['workername_en'],
            'workername_ar' => $validatedData['workername_ar'],
            'workerphone' => $validatedData['workerphone'],
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('item_updated_successfully'),
            ]);
        }

        return redirect()->route('service_site_customer_service.create', $item->id)->with('success', __('item_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = ServiceSiteAndCustomerService::findOrFail($id);
        $item->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('item_deleted_successfully'),
            ]);
        }

        return redirect()->route('service_site_customer_service.create')->with('success', __('item_deleted_successfully'));
    }
}
