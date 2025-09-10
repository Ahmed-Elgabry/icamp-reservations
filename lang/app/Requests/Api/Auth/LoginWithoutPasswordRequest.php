<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class LoginWithoutPasswordRequest extends ApiMasterRequest {


  public function rules() {

        return [

        'phone'        => 'required|numeric|digits_between:9,10|exists:users,phone',
        'code'         => 'required',
        'device_id'    => 'required|max:250',
        'device_type'  => 'required|in:ios,android,web',
        
        ];
  }
}
