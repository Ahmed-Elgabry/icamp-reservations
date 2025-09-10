<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\User;
use App\Notifications\NewDailyReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class DailyReportController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', DailyReport::class);

        $reports = DailyReport::with('employee')
            ->filter(request()->only(['employee_id', 'date_from', 'date_to', 'search']))
            ->latest()
            ->get();

//        $employees = User::whereHas('roles', fn($q) => $q->where('name', 'employee'))->get();
        $employees = User::get();

        return view('dashboard.daily_reports.index', compact('reports', 'employees'));
    }

    public function create()
    {
        return view('dashboard.daily_reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'notes' => 'nullable|string',
            'audio_attachment' => 'nullable|file|max:2048',
            'video_attachment' => 'nullable|file|max:5120',
            'photo_attachment' => 'nullable|image|max:2048',
        ]);

        $attachments = ['audio', 'video', 'photo'];
        foreach ($attachments as $type) {
            if ($request->hasFile("{$type}_attachment")) {
                $validated["{$type}_attachment"] = $request->file("{$type}_attachment")
                    ->store("daily-reports/{$type}s");
            }
        }

        $validated['employee_id'] = auth()->id();

        $report = DailyReport::create($validated);

        $admins = User::where('user_type',1)->get();
        Notification::send($admins, new NewDailyReportNotification($report));

        return redirect()->route('daily-reports.index')
            ->with('success', __('dashboard.report_created'));
    }

    public function show(DailyReport $dailyReport)
    {
        return view('dashboard.daily_reports.show', compact('dailyReport'));
    }

    public function edit(DailyReport $dailyReport)
    {
        return view('dashboard.daily_reports.create', compact('dailyReport'));
    }

    public function update(Request $request, DailyReport $dailyReport)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'notes' => 'nullable|string',
            'audio_attachment' => 'nullable|file|max:2048',
            'video_attachment' => 'nullable|file|max:5120',
            'photo_attachment' => 'nullable|image|max:2048',
            'delete_audio' => 'nullable|boolean',
            'delete_video' => 'nullable|boolean',
            'delete_photo' => 'nullable|boolean',
        ]);

        $attachments = ['audio', 'video', 'photo'];
        foreach ($attachments as $type) {
            // Handle file deletion if checkbox is checked
            if ($request->has("delete_{$type}") && $request->input("delete_{$type}")) {
                if ($dailyReport->{"{$type}_attachment"}) {
                    Storage::delete($dailyReport->{"{$type}_attachment"});
                    $validated["{$type}_attachment"] = null; // Set to null in database
                }
                continue; // Skip file upload processing if deleting
            }

            // Handle file upload if new file is provided
            if ($request->hasFile("{$type}_attachment")) {
                // Delete old file if exists
                if ($dailyReport->{"{$type}_attachment"}) {
                    Storage::delete($dailyReport->{"{$type}_attachment"});
                }
                $validated["{$type}_attachment"] = $request->file("{$type}_attachment")
                    ->store("daily-reports/{$type}s");
            } else {
                // Keep existing file if no new file and not deleting
                unset($validated["{$type}_attachment"]);
            }
        }

        $dailyReport->update($validated);

        return redirect()->route('daily-reports.index')
            ->with('success', __('dashboard.report_updated'));
    }

    public function destroy(DailyReport $dailyReport)
    {
        // Delete attachments
        $attachments = ['audio', 'video', 'photo'];
        foreach ($attachments as $type) {
            if ($dailyReport->{"{$type}_attachment"}) {
                Storage::delete($dailyReport->{"{$type}_attachment"});
            }
        }

        $dailyReport->delete();

        return back()->with('success', __('dashboard.report_deleted'));
    }

    public function exportToPdf()
    {
        // Get filtered reports (same as index method)
        $reports = DailyReport::with('employee')
            ->filter(request()->only(['employee_id', 'date_from', 'date_to', 'search']))
            ->latest()
            ->get();

        // HTML content for PDF
        $html = view('dashboard.daily_reports.pdf', compact('reports'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L' // Landscape for better table display
        ]);

        // Write HTML content
        $mpdf->WriteHTML($html);

        // Output the PDF
        return response($mpdf->Output('daily_reports.pdf', Destination::STRING_RETURN))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="daily_reports.pdf"');
    }
}
