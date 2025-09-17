<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceSiteAndCustomerService;
use App\Helpers\Sanitizer;

class ServiceSiteAndCustomerServiceController extends Controller
{
    public function index()
    {
        $items = ServiceSiteAndCustomerService::orderBy('id', 'desc')->get();
        return view('dashboard.service_site_customer_service.create', compact('items'));
    }

    public function create()
    {
        return view('dashboard.service_site_customer_service.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'serviceSite' => 'required|string',
            'workername_en' => 'required|string',
            'workername_ar' => 'required|string',
            'workerphone' => 'required|string',
        ]);

        $data = Sanitizer::sanitizeArray($data, ['serviceSite','workername_en','workername_ar']);
        ServiceSiteAndCustomerService::create($data);
        return redirect()->route('service_site_customer_service.index')->with('success', 'Created');
    }

    public function edit($id)
    {
        $item = ServiceSiteAndCustomerService::findOrFail($id);
        $items = ServiceSiteAndCustomerService::orderBy('id','desc')->get();
        return view('dashboard.service_site_customer_service.create', compact('item','items'));
    }

    public function update(Request $request, $id)
    {
        $item = ServiceSiteAndCustomerService::findOrFail($id);
        $data = $request->validate([
            'serviceSite' => 'nullable|string',
            'workername_en' => 'nullable|string',
            'workername_ar' => 'nullable|string',
            'workerphone' => 'nullable|string',
        ]);
        $data = Sanitizer::sanitizeArray($data, ['serviceSite','workername_en','workername_ar']);
        $item->update($data);
        return redirect()->route('service_site_customer_service.index')->with('success', 'Updated');
    }

    public function destroy($id)
    {
        $item = ServiceSiteAndCustomerService::findOrFail($id);
        $item->delete();
        return redirect()->route('service_site_customer_service.index')->with('success','Deleted');
    }
}
