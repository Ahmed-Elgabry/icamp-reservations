<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class SendCodeRequest extends ApiMasterRequest {
 

  public function rules() {

        return [

            'phone'        => 'required',

        ];
  }
}
