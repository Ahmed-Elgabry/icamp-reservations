<?php

namespace App\Requests\Api\Orders;

use App\Requests\ApiMasterRequest;

class RateProviderRequest extends ApiMasterRequest
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

            'rate'                     => 'required|in:1,2,3,4,5',
            'provider_id'              => 'required|exists:users,id',
            'comment'                  => 'nullable'
           
        ];
    }
}