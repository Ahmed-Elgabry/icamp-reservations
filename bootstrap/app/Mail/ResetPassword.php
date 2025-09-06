<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

  
    protected $actionUrl;

    public function __construct($actionUrl)
    {
        $this->actionUrl = $actionUrl;
    }

    public function build()
    {
        return $this->view('emails.resetpassword', [
            'actionUrl' => $this->actionUrl,
        ]);
    }
}
