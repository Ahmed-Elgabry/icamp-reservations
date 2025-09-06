<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class ResetPasswordRequest extends ApiMasterRequest {
 
  public function rules() {

        return [

          'password'              => 'required|max:191|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
          'password_confirmation' => 'required|same:password',
          
        ];
  }
}
