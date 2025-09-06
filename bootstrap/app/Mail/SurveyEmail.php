<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SurveyEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * The survey URL.
     *
     * @var string
     */
    public $surveyUrl;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Order $order
     * @param string $surveyUrl
     * @return void
     */
    public function __construct(Order $order, $surveyUrl)
    {
        $this->order = $order;
        $this->surveyUrl = $surveyUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('تقييم نوع المخيم - Service Evaluation')
            ->view('emails.survey')
            ->with([
                'order' => $this->order,
                'surveyUrl' => $this->surveyUrl,
            ]);
    }
}
