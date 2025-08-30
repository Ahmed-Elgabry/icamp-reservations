<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CampReport;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class CampReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', CampReport::class);

        $reports = CampReport::with(['service', 'creator'])
            ->latest()
            ->get();

        return view('dashboard.camp_reports.index', compact('reports'));
    }

    public function create()
    {
        $this->authorize('create', CampReport::class);

        $services = Service::all();
        return view('dashboard.camp_reports.create', compact('services'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', CampReport::class);

        $validated = $request->validate([
            'report_date' => 'required|date',
            'service_id' => 'nullable|exists:services,id',
            'camp_name' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',
            'items' => 'nullable|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.notes' => 'nullable|string',
            'items.*.photo' => 'nullable|image|max:2048',
            'items.*.audio' => 'nullable|file|max:5120',
            'items.*.video' => 'nullable|file|max:10240',
        ]);

        $report = CampReport::create([
            'report_date' => $validated['report_date'],
            'service_id' => $validated['service_id'],
            'camp_name' => $validated['camp_name'],
            'general_notes' => $validated['general_notes'],
            'created_by' => auth()->id(),
        ]);

        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $itemData) {
                $item = $report->items()->create([
                    'item_name' => $itemData['item_name'],
                    'notes' => $itemData['notes'],
                ]);

                // Handle file uploads
                $this->handleItemAttachments($item, $itemData);
            }
        }

        return redirect()->route('camp-reports.index')
            ->with('success', __('dashboard.report_created'));
    }

    public function show(CampReport $campReport)
    {
        $this->authorize('view', $campReport);

        return view('dashboard.camp_reports.show', compact('campReport'));
    }

    public function edit(CampReport $campReport)
    {
        $this->authorize('update', $campReport);

        $services = Service::all();
        return view('dashboard.camp_reports.create', compact('campReport', 'services'));
    }

    public function update(Request $request, CampReport $campReport)
    {
        $this->authorize('update', $campReport);

        $validated = $request->validate([
            'report_date' => 'required|date',
            'service_id' => 'nullable|exists:services,id',
            'camp_name' => 'nullable|string|max:255',
            'general_notes' => 'nullable|string',
            'items' => 'nullable|array|min:1',
            'items.*.id' => 'nullable|exists:camp_report_items,id,camp_report_id,'.$campReport->id,
            'items.*.item_name' => 'required|string|max:255',
            'items.*.notes' => 'nullable|string',
            'items.*.photo' => 'nullable|image|max:2048',
            'items.*.audio' => 'nullable|file|max:5120',
            'items.*.video' => 'nullable|file|max:10240',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'exists:camp_report_items,id,camp_report_id,'.$campReport->id,
            'items.*.remove_photo' => 'nullable|boolean',
            'items.*.remove_audio' => 'nullable|boolean',
            'items.*.remove_video' => 'nullable|boolean',
        ]);

        $campReport->update([
            'report_date' => $validated['report_date'],
            'service_id' => $validated['service_id'],
            'camp_name' => $validated['camp_name'],
            'general_notes' => $validated['general_notes'],
        ]);

        // Delete removed items
        if (!empty($validated['deleted_items'])) {
            $itemsToDelete = $campReport->items()->whereIn('id', $validated['deleted_items'])->get();
            foreach ($itemsToDelete as $item) {
                $this->deleteItemAttachments($item);
                $item->delete();
            }
        }

        // Update or create items
        if (!empty($validated['items'])) {
            foreach ($validated['items'] as $itemData) {
                if (isset($itemData['id'])) {
                    $item = $campReport->items()->findOrFail($itemData['id']);
                    $item->update([
                        'item_name' => $itemData['item_name'],
                        'notes' => $itemData['notes'],
                    ]);

                    // Handle file deletions (if remove_* flag is true)
                    $this->handleItemAttachments($item, $itemData);
                } else {
                    $item = $campReport->items()->create([
                        'item_name' => $itemData['item_name'],
                        'notes' => $itemData['notes'],
                    ]);
                    $this->handleItemAttachments($item, $itemData);
                }
            }
        }

        return redirect()->route('camp-reports.index')
            ->with('success', __('dashboard.report_updated'));
    }

    public function destroy(CampReport $campReport)
    {
        $this->authorize('delete', $campReport);

        foreach ($campReport->items as $item) {
            $this->deleteItemAttachments($item);

            // Delete the entire folder for this item
            $itemFolder = "camp-reports/{$item->id}";
            if (Storage::exists($itemFolder)) {
                Storage::deleteDirectory($itemFolder);
            }
        }

        $campReport->delete();

        return back()->with('success', __('dashboard.report_deleted'));
    }

    protected function handleItemAttachments($item, $itemData)
    {
        $types = ['photo', 'audio', 'video'];

        foreach ($types as $type) {
            // If "remove_{type}" is true, delete the file
            if (isset($itemData["remove_{$type}"]) && $itemData["remove_{$type}"]) {
                if ($item->{"{$type}_path"}) {
                    Storage::delete($item->{"{$type}_path"});
                    $item->update(["{$type}_path" => null]);
                }
            }
            // Handle new file upload
            elseif (isset($itemData[$type]) && $itemData[$type]) {
                // Delete old file if exists
                if ($item->{"{$type}_path"}) {
                    Storage::delete($item->{"{$type}_path"});
                }

                // Store new file
                $path = $itemData[$type]->store("camp-reports/{$item->id}/{$type}s");
                $item->update(["{$type}_path" => $path]);
            }
        }
    }

    protected function deleteItemAttachments($item)
    {
        $types = ['photo', 'audio', 'video'];

        foreach ($types as $type) {
            if ($item->{"{$type}_path"}) {
                Storage::delete($item->{"{$type}_path"});
                $item->update(["{$type}_path" => null]);
            }
        }
    }

    public function exportPdf()
    {
        $reports = CampReport::with(['service', 'creator', 'items'])
            ->latest()
            ->get();

        // Convert images to base64 for PDF embedding
        foreach ($reports as $report) {
            foreach ($report->items as $item) {
                if ($item->photo_path && Storage::exists($item->photo_path)) {
                    try {
                        // Get the file contents and convert to base64
                        $imageData = Storage::get($item->photo_path);
                        $item->photo_base64 = base64_encode($imageData);
                    } catch (\Exception $e) {
                        // If image can't be read, skip it
                        $item->photo_base64 = null;
                    }
                }
            }
        }

        $html = view('dashboard.camp_reports.export', compact('reports'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'P',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
            'margin_header' => 9,
            'margin_footer' => 9,
            // Add RTL support for Arabic
            'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
            // Use fonts that support Arabic characters
            'default_font' => app()->getLocale() === 'ar' ? 'DejaVuSans' : 'DejaVuSans'
        ]);

        // Enable image processing
        $mpdf->showImageErrors = true;

        $mpdf->WriteHTML($html);

        $filename = __('dashboard.camp_reports') . "-" . date('Y-m-d') . ".pdf";
        return $mpdf->Output($filename, 'D');
    }
}
