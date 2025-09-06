<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentLinkCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $customerName;
    public $amount;
    public $description;
    public $orderId;
    public $paymentUrl;
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->customerName = $data['customer_name'];
        $this->amount = $data['amount'];
        $this->description = $data['description'] ?? null;
        $this->orderId = $data['order_id'];
        $this->paymentUrl = $data['payment_url'];
        $this->expiresAt = $data['expires_at'] ?? null;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('dashboard.payment_link_created'))
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.payment_link_created');
    }
}
