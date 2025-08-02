<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class SendEmailCodeRequest extends ApiMasterRequest {
 

  public function rules() {

        return [

            'email' => 'required|email|exists:users,email',

        ];
  }
}
