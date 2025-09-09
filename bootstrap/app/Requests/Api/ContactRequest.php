<?php

namespace App\Requests\Api;

use App\Requests\ApiMasterRequest;

class ContactRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

            'name'                      => 'required|max:255',
            'country_code'              => 'required',
            'phone'                     => 'required|max:14',
            'email'                     => 'required|email',
            'message'                   => 'required',
        ];
    }
}