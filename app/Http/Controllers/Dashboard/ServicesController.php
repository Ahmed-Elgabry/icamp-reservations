<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesController extends Controller
{
    // Display a listing of services
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();
        return view('dashboard.services.index', compact('services'));
    }

    // Show the form for creating a new service
    public function create()
    {
        $stocks = Stock::all();
        return view('dashboard.services.create', compact('stocks'));
    }

    // Store a newly created service in the database
    public function store(Request $request)
{
    try {
        // التحقق من صحة البيانات الواردة
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'hours' => 'required|integer',
            'hour_from' => 'required',
            'hour_to' => 'required',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|distinct|min:1',
            'counts' => 'nullable|array',
            'counts.*' => 'nullable|integer|min:1',
            'reports' => 'nullable|array',
            'reports.*' => 'required|string|max:255',
            'reports_counts' => 'nullable|array',
            'reports_counts.*' => 'required|integer|min:1',
            'reports_images' => 'nullable|array',
            'reports_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'report_orders' => 'nullable|array',
            'report_orders.*' => 'nullable|integer',
        ]);

        // تحديد الحقول المطلوبة فقط لإنشاء الخدمة
        $serviceData = $request->only(['name', 'description', 'price', 'hours', 'hour_from', 'hour_to']);

        // إنشاء الخدمة
        $service = Service::create($serviceData);

        // التعامل مع المخزون
        if (isset($request->stocks) && isset($request->counts)) {
            $stocksData = array_combine($request->stocks, $request->counts);
            $service->stocks()->sync([]);

            foreach ($stocksData as $stockId => $count) {
                $service->stocks()->attach($stockId, ['count' => $count]);
            }
        }

        // التعامل مع التقارير
        if (isset($request->reports) && isset($request->reports_counts)) {
            foreach ($request->reports as $index => $reportName) {
                $count = $request->reports_counts[$index];
                $order = $request->report_orders[$index] ?? $index;

                // إنشاء التقرير
                $newReport = $service->reports()->create([
                    'name' => $reportName,
                    'count' => $count,
                    'report_orders' => $order
                ]);

                if ($files = $request->file("reports_images.{$index}")) {
                    $path = $files->store('reports', 'public');
                    $newReport->create([
                        'image' => $path
                    ]);
                }
            }
        }

        // الرد بنجاح مع رسالة
        return response()->json([
            'message' => __('dashboard.service_created_successfully'),
            'redirect' => route('services.index') // أو المسار الذي تريده
        ], 201); // 201 Created
    } catch (\Exception $e) {
        // تسجيل الخطأ في السجلات
        \Log::error('Error creating service: '.$e->getMessage());

        // الرد بخطأ داخلي في الخادم مع تفاصيل الخطأ (للتطوير فقط)
        return response()->json([
            'errors' => ['server' => $e->getMessage()] // عرض رسالة الخطأ الحقيقية
        ], 500);
    }
}


    // Display the specified service
    public function show(Service $service)
    {
        return view('dashboard.services.show', compact('service'));
    }

    // Show the form for editing the specified service
    public function edit($service)
    {
        $service = Service::findOrFail($service);
        $stocks = Stock::all();
        $reports = ServiceReport::where('service_id', $service->id)->orderBy('id')->get();

        return view('dashboard.services.create', compact('service', 'stocks', 'reports'));
    }

    // Update the specified service in the database
    public function update(Request $request, $service)
    {
        $service = Service::findOrFail($service);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'hours' => 'required|integer',
            'hour_from' => 'required',
            'hour_to' => 'required',
            'stocks' => 'nullable|array',
            'stocks.*' => 'nullable|distinct|min:1',
            'counts' => 'nullable|array',
            'counts.*' => 'nullable|integer|min:1',
            'report_orders' => 'nullable|array',
            'report_orders.*' => 'nullable|integer',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        unset($validatedData['stocks']);
        unset($validatedData['counts']);
        unset($validatedData['report_orders']);
        $service->update($validatedData);

        $service->stocks()->sync([]);

        if (isset($request->stocks) && isset($request->counts)) {
            $stocksData = array_combine($request->stocks, $request->counts);

            foreach ($stocksData as $stockId => $count) {
                $service->stocks()->attach($stockId, ['count' => $count]);
            }
        }

        // Update or create reports
        if (isset($request->reports) && isset($request->reports_counts)) {
            $existingReports = $service->reports()->pluck('id', 'id')->toArray();
            $updatedReports = [];

            foreach ($request->reports as $index => $reportName) {
                $count = $request->reports_counts[$index];
                $reportId = $request->report_ids[$index] ?? null;

                $reportData = [
                    'name' => $reportName,
                    'count' => $count,
                    // 'order' => $order,
                    'service_id' => $service->id
                ];

  if ($reportId && isset($existingReports[$reportId])) {
    // Update existing report
    $report = ServiceReport::findOrFail($reportId);
    $report->update($reportData);

    // Get the image for this index correctly
    $file = $request->file('reports_images')[$index] ?? null;

    if ($file instanceof \Illuminate\Http\UploadedFile) {
        // Delete the old image if it exists
        if ($report->image && Storage::disk('public')->exists($report->image)) {
            Storage::disk('public')->delete($report->image);
        }

        // Store new image
        $path = $file->store('reports', 'public');
        $report->update(['image' => $path]);
    }

    $updatedReports[] = $reportId;
    unset($existingReports[$reportId]);
} else {
    // Creating a new report
    $file = $request->file('reports_images')[$index] ?? null;

    if ($file instanceof \Illuminate\Http\UploadedFile) {
        $reportData['image'] = $file->store('reports', 'public');
    }

    $newReport = ServiceReport::create($reportData);
    $updatedReports[] = $newReport->id;
}

            }

            ServiceReport::whereIn('id', array_keys($existingReports))->delete();
        } else {
            $service->reports()->delete();
        }

        return response()->json();
    }

    // Remove the specified service from the database
    public function destroy($service)
    {
        $service = Service::findOrFail($service);
        $service->delete();
        return response()->json();
    }

    public function deleteAll(Request $request)
    {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
            $ids[] = $id->id;
        }
        if (Service::whereIn('id', $ids)->delete()) {
            return response()->json('success');
        } else {
            return response()->json('failed');
        }
    }
}
