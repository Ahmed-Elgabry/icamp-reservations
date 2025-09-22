<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ManualWhatsappSend;
use App\Models\WhatsappMessageTemplate;
use App\Models\Customer;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ManualWhatsappSendController extends Controller
{
    /**
     * Display a listing of manual WhatsApp sends.
     */
    public function index()
    {
        $sends = ManualWhatsappSend::with(['template', 'creator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.manual_whatsapp_sends.index', compact('sends'));
    }

    /**
     * Show the form for creating a new manual WhatsApp send.
     */
    public function create()
    {
        $manualTemplates = WhatsappMessageTemplate::active()
            ->ofType('manual_template')
            ->get();

        $customers = Customer::with(['orders'])
            ->whereNotNull('phone')
            ->orderBy('name')
            ->get();

        return view('dashboard.manual_whatsapp_sends.create', compact('manualTemplates', 'customers'));
    }

    /**
     * Store a newly created manual WhatsApp send.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'template_id' => 'required|exists:whatsapp_message_templates,id',
            'customer_ids' => 'nullable|array',
            'customer_ids.*' => 'exists:customers,id',
            'manual_numbers' => 'nullable|array',
            'manual_numbers.*' => 'string|regex:/^[0-9+\-\s()]+$/',
            'custom_message' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
        ]);

        try {
            // Handle file uploads
            $attachmentPaths = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('manual_whatsapp_attachments', $filename, 'public');
                    $attachmentPaths[] = $path;
                }
            }

            // Create the manual send record
            $manualSend = ManualWhatsappSend::create([
                'title' => $request->title,
                'template_id' => $request->template_id,
                'customer_ids' => $request->customer_ids ?? [],
                'manual_numbers' => $request->manual_numbers ?? [],
                'attachments' => $attachmentPaths,
                'custom_message' => $request->custom_message,
                'status' => 'pending',
                'total_count' => count($request->customer_ids ?? []) + count($request->manual_numbers ?? []),
                'created_by' => Auth::id(),
            ]);

            // Process the sending in the background
            $this->processManualSend($manualSend);

            return redirect()->route('manual-whatsapp-sends.index')
                ->with('success', __('dashboard.manual_whatsapp_send_created_successfully'));
        } catch (\Exception $e) {
            Log::error('Manual WhatsApp send creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', __('dashboard.manual_whatsapp_send_creation_failed'));
        }
    }

    /**
     * Display the specified manual WhatsApp send.
     */
    public function show(ManualWhatsappSend $manualWhatsappSend)
    {
        $manualWhatsappSend->load(['template', 'creator']);
        return view('dashboard.manual_whatsapp_sends.show', compact('manualWhatsappSend'));
    }

    /**
     * Process the manual send (send messages).
     */
    private function processManualSend(ManualWhatsappSend $manualSend)
    {
        try {
            $manualSend->update(['status' => 'sending']);

            $template = $manualSend->template;
            $whatsAppService = new WhatsAppService();
            $phoneNumbers = $manualSend->getAllPhoneNumbers();
            $sendResults = [];
            $sentCount = 0;
            $failedCount = 0;

            foreach ($phoneNumbers as $phoneData) {
                try {
                    // Prepare message
                    $message = $template->getBilingualMessage($phoneData['name']);

                    // Add custom message if provided
                    if ($manualSend->custom_message) {
                        $message .= "\n\n" . $manualSend->custom_message;
                    }

                    $success = false;

                    // Send with attachments if any
                    if (!empty($manualSend->attachments)) {
                        // For now, send the first attachment as a file
                        $attachmentPath = Storage::disk('public')->path($manualSend->attachments[0]);
                        if (file_exists($attachmentPath)) {
                            $success = $whatsAppService->sendFile(
                                $phoneData['phone'],
                                $attachmentPath,
                                $message
                            );
                        }
                    } else {
                        // Send as text message
                        $success = $whatsAppService->sendTextMessage(
                            $phoneData['phone'],
                            $message
                        );
                    }

                    if ($success) {
                        $sentCount++;
                        $sendResults[] = [
                            'phone' => $phoneData['phone'],
                            'name' => $phoneData['name'],
                            'status' => 'success',
                            'message' => 'Message sent successfully'
                        ];
                    } else {
                        $failedCount++;
                        $sendResults[] = [
                            'phone' => $phoneData['phone'],
                            'name' => $phoneData['name'],
                            'status' => 'failed',
                            'message' => 'Failed to send message'
                        ];
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    $sendResults[] = [
                        'phone' => $phoneData['phone'],
                        'name' => $phoneData['name'],
                        'status' => 'failed',
                        'message' => $e->getMessage()
                    ];
                }
            }

            // Update the manual send with results
            $manualSend->update([
                'status' => $failedCount > 0 ? 'failed' : 'completed',
                'send_results' => $sendResults,
                'sent_count' => $sentCount,
                'failed_count' => $failedCount,
            ]);
        } catch (\Exception $e) {
            Log::error('Manual WhatsApp send processing failed', [
                'manual_send_id' => $manualSend->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $manualSend->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get customers for AJAX search.
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->get('q', '');

        $customers = Customer::with(['orders'])
            ->whereNotNull('phone')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->limit(20)
            ->get()
            ->map(function ($customer) {
                $latestOrder = $customer->orders()->latest()->first();
                return [
                    'id' => $customer->id,
                    'text' => $customer->name . ' (' . $customer->phone . ')' .
                        ($latestOrder ? ' - Order: ' . $latestOrder->order_number : ''),
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'order_number' => $latestOrder ? $latestOrder->order_number : null
                ];
            });

        return response()->json(['results' => $customers]);
    }
}
