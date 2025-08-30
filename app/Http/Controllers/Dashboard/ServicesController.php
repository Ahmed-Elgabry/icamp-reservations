<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceReport;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ServicesController extends Controller
{
    // Display a listing of services
    public function index()
    {
        $this->authorize('viewAny', Service::class);
        $services = Service::orderBy('created_at', 'desc')->get();
        return view('dashboard.services.index', compact('services'));
    }

    // Show the form for creating a new service
    public function create()
    {
        $this->authorize('create', Service::class);
        $stocks = Stock::all();
        return view('dashboard.services.create', compact('stocks'));
    }

    // Store a newly created service in the database
    public function store(Request $request)
    {
        $this->authorize('create', Service::class);

        try {
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

            \DB::beginTransaction();
            $request['registeration_forms'] = $request['registeration_forms'] == 'on' ? 1 : 0;
            $serviceData = $request->only(['name', 'description', 'price', 'hours', 'hour_from', 'hour_to','registeration_forms']);
            $service = Service::create($serviceData);

            if (isset($request->stocks) && isset($request->counts)) {
                $stocksData = array_combine($request->stocks, $request->counts);
                $service->stocks()->sync([]);

                foreach ($stocksData as $stockId => $count) {
                    $service->stocks()->attach($stockId, ['count' => $count]);
                }
            }
            if (isset($request->reports) && isset($request->reports_counts)) {
                foreach ($request->reports as $index => $reportName) {
                    $count = $request->reports_counts[$index];
                    $order = $request->report_orders[$index] ?? $index;
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
            \DB::commit();
            return response()->json([
                'message' => __('dashboard.service_created_successfully'),
                'redirect' => route('services.index')
            ], 201);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating service: '.$e->getMessage());
            return response()->json([
                'errors' => ['server' => $e->getMessage()]
            ], 500);
        }
    }

    // Display the specified service
    public function show(Service $service)
    {
        $this->authorize('view', $service);
        return view('dashboard.services.show', compact('service'));
    }

    // Show the form for editing the specified service
    public function edit($service)
    {
        $service = Service::findOrFail($service);
        $this->authorize('update', $service);
        $stocks = Stock::all();
        $reports = ServiceReport::where('service_id', $service->id)
                                    ->orderBy('ordered_count')
                                    ->get();

        return view('dashboard.services.create', compact('service', 'stocks', 'reports'));
    }

    public function update(Request $request, $serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $this->authorize('update', $service);

        $validated = $request->validate([
            'name'           => 'required|max:255',
            'description'    => 'nullable',
            'price'          => 'required|numeric',
            'hours'          => 'required|integer',
            'hour_from'      => 'required',
            'registeration_forms' => 'nullable',
            'hour_to'        => 'required',
            'stocks'         => 'nullable|array',
            'stocks.*'       => 'nullable|distinct|exists:stocks,id',
            'counts'         => 'nullable|array',
            'counts.*'       => 'nullable|integer|min:1',
            'report_orders'  => 'nullable|array',
            'report_orders.*'=> 'nullable|integer',
            'images'         => 'nullable|array',
            'images.*'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $requestedCounts = collect($validated['stocks'] ?? [])
            ->mapWithKeys(function ($id, $idx) use ($validated) {
                $sid = (int) $id;
                $cnt = (int) ($validated['counts'][$idx] ?? 0);
                return $sid ? [$sid => $cnt] : [];
            })
            ->filter(fn ($c) => $c > 0)
            ->reduce(function ($carry, $count, $stockId) {
                $carry[$stockId] = ($carry[$stockId] ?? 0) + $count;
                return $carry;
            }, []);

       try {
            \DB::beginTransaction();
            $existingPivot = $service->stocks()
                ->get()
                ->mapWithKeys(fn ($s) => [(int)$s->id => (int)$s->pivot->count]);
            $existingPivotKeys = $existingPivot->keys()->all();

            $lockIds = array_values(array_unique(array_merge(
                array_keys($requestedCounts),
                $existingPivotKeys
            )));
            $stockModels = Stock::whereIn('id', $lockIds)->lockForUpdate()->get()->keyBy('id');

            foreach ($requestedCounts as $stockId => $newCount) {
                $stock = $stockModels[$stockId] ?? null;
                if (!$stock) {
                    throw new \RuntimeException("Stock #{$stockId} not found.");
                }
                $old   = $existingPivot[$stockId] ?? 0;
                $delta = $newCount - $old;
                if ($delta > 0 && (int)$stock->quantity < $delta) {
                    throw ValidationException::withMessages([
                        'stocks' => ["Insufficient quantity for '{$stock->name}'. Need {$delta}, available {$stock->quantity}."],
                    ]);
                }
            }

            $syncPayload = [];
            foreach ($requestedCounts as $stockId => $count) {
                $syncPayload[$stockId] = ['count' => $count];
            }
            $service->stocks()->sync($syncPayload);
            $service->update([
                'name'        => $validated['name'],
                'description' => $validated['description'] ?? null,
                'price'       => $validated['price'],
                'hours'       => $validated['hours'],
                'registeration_forms' => isset($validated['registeration_forms']) ? ($validated['registeration_forms'] == 'on' ? 1 : 0) : 0,
                'hour_from'   => $validated['hour_from'],
                'hour_to'     => $validated['hour_to'],
            ]);

            if ($request->filled('reports') && $request->filled('reports_counts')) {
                $existingReports = $service->reports()->pluck('id', 'id')->toArray();
                $updatedReports  = [];

                foreach ($request->reports as $index => $reportName) {
                    $count    = (int) ($request->reports_counts[$index] ?? 0);
                    $reportId = $request->report_ids[$index] ?? null;
                    $reportData = [
                        'name'       => $reportName,
                        'count'      => $count,
                        'service_id' => $service->id,
                    ];

                    if ($reportId && isset($existingReports[$reportId])) {
                        $report = ServiceReport::findOrFail($reportId);
                        $report->update($reportData);

                        $file = $request->file('reports_images')[$index] ?? null;
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            if ($report->image && Storage::disk('public')->exists($report->image)) {
                                Storage::disk('public')->delete($report->image);
                            }
                            $path = $file->store('reports', 'public');
                            $report->update(['image' => $path]);
                        }

                        $updatedReports[] = $reportId;
                        unset($existingReports[$reportId]);
                    } else {
                        $file = $request->file('reports_images')[$index] ?? null;
                        if ($file instanceof \Illuminate\Http\UploadedFile) {
                            $reportData['image'] = $file->store('reports', 'public');
                        }
                        $newReport = ServiceReport::create($reportData);
                        $updatedReports[] = $newReport->id;
                    }
                }

                if (!empty($existingReports)) {
                    ServiceReport::whereIn('id', array_keys($existingReports))->delete();
                }
            } else {
                $service->reports()->delete();
            }

            \DB::commit();
            return response()->json();
        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::error('Service update failed: '.$e->getMessage());
            return response()->json([
                'errors' => ['server' => $e->getMessage()],
            ], 500);
        }
    }

    // Remove the specified service from the database
    public function destroy($service)
    {
        $service = Service::findOrFail($service);
        $this->authorize('delete', $service);
        $service->delete();
        return response()->json();
    }

    public function deleteAll(Request $request)
    {
        $this->authorize('delete', Service::class);

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

    public function move(Request $request, Service $service, ServiceReport $report)
    {
        $this->authorize('move', Service::class);

        $data = $request->validate([
            'direction' => 'required|in:up,down',
        ]);

        return \DB::transaction(function () use ($service, $report, $data) {
            $this->normalizeOrderedCounts($service);
            $current = ServiceReport::where('service_id', $service->id)
                ->where('id', $report->id)
                ->lockForUpdate()
                ->firstOrFail();

            $targetOrder = $data['direction'] === 'up' ? $current->ordered_count - 1 : $current->ordered_count + 1;

            if ($targetOrder < 1) {
                return response()->json(['message' => 'noop', 'moved' => false], 200);
            }

            $max = (int) ServiceReport::where('service_id', $service->id)->max('ordered_count');
            if ($targetOrder > $max) {
                return response()->json(['message' => 'noop', 'moved' => false], 200);
            }

            $neighbor = ServiceReport::where('service_id', $service->id)
                ->where('ordered_count', $targetOrder)
                ->lockForUpdate()
                ->first();

            if (!$neighbor) {
                $this->normalizeOrderedCounts($service);
                $neighbor = ServiceReport::where('service_id', $service->id)
                    ->where('ordered_count', $targetOrder)
                    ->lockForUpdate()
                    ->first();

                if (!$neighbor) {
                    return response()->json(['message' => 'noop', 'moved' => false], 200);
                }
            }

            $cur = $current->ordered_count;
            $nbr = $neighbor->ordered_count;

            $current->ordered_count = $nbr;
            $neighbor->ordered_count = $cur;

            $current->save();
            $neighbor->save();

            return response()->json([
                'message' => 'ok',
                'moved'   => true,
                'current' => ['id' => $current->id, 'ordered_count' => $current->ordered_count],
                'neighbor'=> ['id' => $neighbor->id, 'ordered_count' => $neighbor->ordered_count],
            ], 200);
        });
    }
    private function normalizeOrderedCounts(Service $service): void
    {
        $rows = ServiceReport::where('service_id', $service->id)
            ->orderByRaw('CASE WHEN ordered_count IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('ordered_count')
            ->orderBy('id')
            ->lockForUpdate()
            ->get();

        $i = 1;
        foreach ($rows as $r) {
            if ((int)$r->ordered_count !== $i) {
                $r->ordered_count = $i;
                $r->save();
            }
            $i++;
        }
    }

}
