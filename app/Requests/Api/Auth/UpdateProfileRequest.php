<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;

class UpdateProfileRequest extends ApiMasterRequest {

    public function rules() {

        return [

            'user_type'         => 'required|in:user,provider',
            'image'             => 'nullable|image',
            'first_name'        => 'nullable|max:255',
            'last_name'         => 'nullable|max:255',
            'phone'             => 'nullable|numeric|digits_between:9,10|unique:users,phone,'.auth()->id(),
            'password'          => 'nullable|max:191|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'device_id'         => 'required|max:250',
            'device_type'       => 'required|in:ios,android,web',

        ];
    }
}
