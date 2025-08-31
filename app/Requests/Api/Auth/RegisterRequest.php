<?php

namespace App\Requests\Api\Auth;

use App\Requests\ApiMasterRequest;

class RegisterRequest extends ApiMasterRequest {

    public function rules() {

        return [

            'first_name'            => 'required|max:255',
            'last_name'             => 'required|max:255',
            'phone'                 => 'required|unique:users,phone',
            'email'                 => 'required|email|unique:users,email',
            'device_id'             => 'required|max:250',
            'device_type'           => 'required|in:ios,android,web',
            'job'                   => 'required',
            'image'                 => 'nullable|image',
            'password'              => 'required|max:191|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x]).*$/',
            'password_confirmation' => 'required|same:password',

        ];
    }
}
