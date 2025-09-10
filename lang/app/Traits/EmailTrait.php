<?php

namespace App\Traits;

use App\Mail\VerfiyMail;
use Illuminate\Support\Facades\Mail;

trait EmailTrait {


    public function sendMail($email, $msg)
    {
      try {

        return  Mail::to($email)->send(new VerfiyMail(['title' => __('dashboard.account_verification'), 'message' => $msg]));

      } catch (\Throwable $th) {
         return $th;
      }
    }
}