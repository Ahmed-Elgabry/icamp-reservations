<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $token               = '';

    public function setToken($value) {
      $this->token = $value;
      return $this;
    }
  
    public function toArray($request) {
      return [
        'id'                  => $this->id,
        'first_name'          => $this->first_name,
        'last_name'           => $this->last_name,
        'email'               => $this->email,
        'country_code'        => $this->country_code,
        'phone'               => $this->phone,
        'full_phone'          => $this->full_phone,
        'image'               => $this->image,
        'lang'                => $this->lang,
        'is_notify'           => $this->is_notify,
        'token'               => $this->token,
      
        'provider'            => $this->when($this->user_type , $this->user_type == 3 ? [
                              'certificates'        => CertificateResource::collection($this->certificates),
                              'categories'          => ProviderCategoriesResource::collection($this->categories),
                              'workdays'            => WorkdayResource::collection($this->workdays),
                              'rate'                => $this->rates->avg('rate') == null ? 0 : $this->rates->avg('rate'),
                              'rate_count'          =>  $this->rates->count(),
                      ] : null)
       
  
      ];
    }
}
