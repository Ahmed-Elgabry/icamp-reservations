<?php

namespace App\Requests\Api\Auth;
use App\Requests\ApiMasterRequest;
use Illuminate\Http\Request;

class UserCompleteDataRequest extends ApiMasterRequest {
  
 
  public function rules() {

        return [
        'name'         => 'required|max:50',
        'phone'        => 'required|numeric|digits_between:9,10|unique:users,phone',
        'email'        => 'required|email|unique:users,email|max:50',
        'image'        => 'nullable',
        ];
  }

}
