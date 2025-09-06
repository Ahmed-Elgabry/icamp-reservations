<?php

namespace App\Requests\Api;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class ActivateRequest extends ApiMasterRequest {
 
  public function rules() {

        return [

            'code'         => 'required|max:10',
            'phone'        => 'required|exists:users,phone',
            'device_id'    => 'required|max:250',
            'device_type'  => 'required|in:ios,android,web',
        ];
  }
}
