<?php

namespace App\Requests\Api\Orders;

use App\Requests\ApiMasterRequest;

class searchProviderRequest extends ApiMasterRequest
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

            'word'                     => 'required',
            'category_id'              => 'nullable|exists:categories,id',
            'rate'                     => 'nullable|in:highest,lowest'
           
        ];
    }
}