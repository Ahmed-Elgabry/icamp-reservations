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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:3000',
            'terms' => 'nullable|string|max:3000',
            'commercial_license' => 'nullable|',
            'company_name' => 'nullable|string|max:255',
            'order_id' => 'nullable|integer|exists:orders,id',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
