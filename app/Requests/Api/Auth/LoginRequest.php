<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class LoginRequest extends ApiMasterRequest {
 
  public function rules() {

        return [

            'phone'        => 'required|exists:users,phone',
            'device_id'    => 'required|max:250',
            'device_type'  => 'required|in:ios,android,web',
            'password'     => 'required|min:6'
        ];
  }
}
