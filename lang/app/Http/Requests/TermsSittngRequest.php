<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TermsSittngRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'commercial_license_ar' => 'nullable|string',
            'commercial_license_en' => 'nullable|string',
            'order_id' => 'nullable|integer|exists:orders,id',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
