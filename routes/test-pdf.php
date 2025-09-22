<?php

use App\Http\Controllers\Dashboard\ContactGuideController;
use App\Models\ContactGuide;
use Illuminate\Support\Facades\Route;

// Test PDF Generation
Route::get('/test-contact-pdf', function () {
    // Create test data
    $testContact = new ContactGuide([
        'entity_name' => 'شركة تجريبية',
        'contact_person_name' => 'أحمد محمد',
        'primary_phone' => '123456789',
        'secondary_phone' => '987654321',
        'fixed_phone' => '1234567',
        'email' => 'test@example.com',
        'notes' => 'هذا نص تجريبي باللغة العربية',
    ]);
    
    $contacts = collect([$testContact]);
    $locale = 'ar';
    
    // Generate PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.contact_guides.pdf', compact('contacts', 'locale'));
    $pdf->setPaper('a4', 'landscape');
    
    // Save the PDF to a temporary file
    $tempFile = storage_path('app/test-contact-export.pdf');
    file_put_contents($tempFile, $pdf->output());
    
    return response()->json([
        'success' => true,
        'message' => 'PDF generated successfully',
        'path' => $tempFile,
        'download_url' => url('download-test-pdf')
    ]);
});

// Route to download the generated PDF
Route::get('/download-test-pdf', function () {
    $file = storage_path('app/test-contact-export.pdf');
    
    if (!file_exists($file)) {
        return response('File not found', 404);
    }
    
    return response()->download($file, 'test-contact-export.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
});
