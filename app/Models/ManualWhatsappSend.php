<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualWhatsappSend extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'template_id',
        'customer_ids',
        'manual_numbers',
        'attachments',
        'custom_message',
        'status',
        'send_results',
        'sent_count',
        'failed_count',
        'total_count',
        'error_message',
        'created_by',
    ];

    protected $casts = [
        'customer_ids' => 'array',
        'manual_numbers' => 'array',
        'attachments' => 'array',
        'send_results' => 'array',
    ];

    /**
     * Get the template used for this send.
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(WhatsappMessageTemplate::class, 'template_id');
    }

    /**
     * Get the user who created this send.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the customers for this send.
     */
    public function customers()
    {
        return Customer::whereIn('id', $this->customer_ids ?? []);
    }

    /**
     * Get all phone numbers (customers + manual numbers).
     */
    public function getAllPhoneNumbers(): array
    {
        $phoneNumbers = [];
        
        // Add customer phone numbers
        $customers = Customer::whereIn('id', $this->customer_ids ?? [])->get();
        foreach ($customers as $customer) {
            if ($customer->phone) {
                $phoneNumbers[] = [
                    'phone' => $customer->phone,
                    'name' => $customer->name,
                    'type' => 'customer',
                    'customer_id' => $customer->id
                ];
            }
        }
        
        // Add manual phone numbers
        foreach ($this->manual_numbers ?? [] as $number) {
            $phoneNumbers[] = [
                'phone' => $number,
                'name' => 'Manual Number',
                'type' => 'manual'
            ];
        }
        
        return $phoneNumbers;
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-light-warning',
            'sending' => 'badge-light-info',
            'completed' => 'badge-light-success',
            'failed' => 'badge-light-danger',
            default => 'badge-light-secondary'
        };
    }

    /**
     * Get status label.
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => __('dashboard.pending'),
            'sending' => __('dashboard.sending'),
            'completed' => __('dashboard.completed'),
            'failed' => __('dashboard.failed'),
            default => __('dashboard.unknown')
        };
    }
}