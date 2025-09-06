<?php

namespace App\Requests\Api\Orders;

use App\Requests\ApiMasterRequest;

class sendOrderRequest extends ApiMasterRequest
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

            'title'                     => 'required|max:255',
            'desc'                      => 'required',
            'contact_type'              => 'required|in:call,video,chat',
            'provider_id'               => 'required|exists:users,id',
            'consult_date'              => 'required',
            'consult_time'              => 'required',
            'category_id'               => 'required|exists:categories,id'
        ];
    }
}