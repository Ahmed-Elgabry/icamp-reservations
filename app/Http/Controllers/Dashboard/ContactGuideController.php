<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class ContactGuideController extends Controller
{
    public function index()
    {
        $contacts = ContactGuide::latest()->get();
        return view('dashboard.contact_guides.index', compact('contacts'));
    }

    public function create()
    {
        return view('dashboard.contact_guides.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'entity_name' => 'nullable|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'primary_phone' => 'required|string|max:50',
            'secondary_phone' => 'required|string|max:50',
            'fixed_phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'photo' => 'required|image|max:10000',
            'notes' => 'required|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('contact_photos' , 'public');
        }

        ContactGuide::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('dashboard.item_created_successfully'),
            ]);
        }

        return redirect()->route('contact-guides.index')->with('success', __('dashboard.item_created_successfully'));
    }

    public function edit(ContactGuide $contact_guide)
    {
        return view('dashboard.contact_guides.create', ['contact' => $contact_guide]);
    }

    public function update(Request $request, ContactGuide $contact_guide)
    {
        $validated = $request->validate([
            'entity_name' => 'nullable|string|max:255',
            'contact_person_name' => 'required|string|max:255',
            'primary_phone' => 'required|string|max:50',
            'secondary_phone' => 'required|string|max:50',
            'fixed_phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'photo' => 'nullable|image|max:10000',
            'notes' => 'required|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($contact_guide->photo) {
                Storage::delete($contact_guide->photo);
            }
            $validated['photo'] = $request->file('photo')->store('contact_photos');
        }

        $contact_guide->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('dashboard.item_updated_successfully'),
            ]);
        }

        return redirect()->route('contact-guides.index')->with('success', __('dashboard.item_updated_successfully'));
    }

    public function destroy(ContactGuide $contact_guide)
    {
        if ($contact_guide->photo) {
            Storage::delete($contact_guide->photo);
        }
        $contact_guide->delete();
        return redirect()->route('contact-guides.index')->with('success', __('dashboard.item_deleted_successfully'));
    }

    /**
     * Export contacts to PDF
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $contacts = ContactGuide::latest()->get();
        
        // Set locale for PDF generation
        $locale = App::getLocale();
        $pdf = PDF::loadView('dashboard.contact_guides.pdf', compact('contacts', 'locale'));
        
        // Set paper size and orientation
        $pdf->setPaper('a4', 'landscape');
        
        // Set filename with current date
        $filename = 'contacts_export_' . now()->format('Y-m-d') . '.pdf';
        
        // Stream the PDF to the browser
        return $pdf->download($filename);
    }
}
