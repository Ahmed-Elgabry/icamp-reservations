<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;

class PromoteProfileRequest extends ApiMasterRequest {

  public function rules() {
    return [
     
     
        'identity_image'        => 'required|image',
        'car_license'           => 'required|image' ,
        'car_image'             => 'required|image', 
        'front_car_image'       => 'required|image',
        'back_car_image'        => 'required|image', 
   
    ];
  }
}
