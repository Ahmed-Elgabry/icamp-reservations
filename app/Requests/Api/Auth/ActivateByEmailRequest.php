<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class ActivateByEmailRequest extends ApiMasterRequest {
 
  public function rules() {

        return [

            'code'         => 'required|max:10',
            'email'        => 'required|exists:users,email',
            'device_id'    => 'required|max:250',
            'device_type'  => 'required|in:ios,android,web',
        ];
  }
}
