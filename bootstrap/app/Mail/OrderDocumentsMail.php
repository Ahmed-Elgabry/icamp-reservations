<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OrderDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $documents;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, array $documents, $subject = null)
    {
        $this->order = $order;
        $this->documents = $documents;
        $this->subject = $subject ?? 'مستندات الحجز من Funcamp';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->subject)
            ->view('emails.order_documents');

        // Attach PDFs based on selected documents
        foreach ($this->documents as $document) {
            // Handle receipt types with IDs
            if (str_starts_with($document, 'addon_receipt_')) {
                $addonId = str_replace('addon_receipt_', '', $document);
                $pdfPath = storage_path('app/temp/addon_receipt_' . $addonId . '.pdf');
            } elseif (str_starts_with($document, 'payment_receipt_')) {
                $paymentId = str_replace('payment_receipt_', '', $document);
                $pdfPath = storage_path('app/temp/payment_receipt_' . $paymentId . '.pdf');
            } elseif (str_starts_with($document, 'warehouse_receipt_')) {
                $itemId = str_replace('warehouse_receipt_', '', $document);
                $pdfPath = storage_path('app/temp/warehouse_receipt_' . $itemId . '.pdf');
            } else {
                $pdfPath = storage_path('app/temp/' . $document . '_' . $this->order->id . '.pdf');
            }

            // Check if file exists before attaching
            if (file_exists($pdfPath)) {
                try {
                    $filename = $this->getDocumentFilename($document);
                    $email->attach($pdfPath, [
                        'as' => $filename,
                        'mime' => 'application/pdf',
                    ]);
                } catch (\Exception $e) {
                    // Log the error but continue with other attachments
                    Log::error('Failed to attach document: ' . $document . ' for order: ' . $this->order->id .
                        '. Error: ' . $e->getMessage());
                }
            } else {
                Log::warning('PDF file not found for attachment: ' . $pdfPath);
            }
        }

        return $email;
    }

    /**
     * Get the appropriate filename for each document type
     *
     * @param string $documentType
     * @return string
     */
    private function getDocumentFilename($documentType)
    {
        // Handle receipt types with IDs
        if (str_starts_with($documentType, 'addon_receipt_')) {
            $addonId = str_replace('addon_receipt_', '', $documentType);
            return 'إيصال_إضافة_' . $addonId . '.pdf';
        } elseif (str_starts_with($documentType, 'payment_receipt_')) {
            $paymentId = str_replace('payment_receipt_', '', $documentType);
            return 'إيصال_دفع_' . $paymentId . '.pdf';
        } elseif (str_starts_with($documentType, 'warehouse_receipt_')) {
            $itemId = str_replace('warehouse_receipt_', '', $documentType);
            return 'إيصال_مخزن_' . $itemId . '.pdf';
        }

        $filenames = [
            'show_price' => 'عرض_السعر.pdf',
            'reservation_data' => 'بيانات_الحجز.pdf',
            'invoice' => 'الفاتورة.pdf',
            'receipt' => 'إيصال_القبض.pdf',
        ];

        return $filenames[$documentType] ?? 'document.pdf';
    }
}
