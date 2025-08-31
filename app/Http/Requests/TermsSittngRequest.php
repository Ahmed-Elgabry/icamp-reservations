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
            // 'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048 ', will deleted
            // 'description' => 'nullable|string|max:3000',
            // 'terms' => 'nullable|string|max:3000',
            'commercial_license_ar' => 'nullable|string',
            'commercial_license_en' => 'nullable|string',
            // 'company_name' => 'nullable|string|max:255',
            'order_id' => 'nullable|integer|exists:orders,id',
            'user_id' => 'nullable|integer|exists:users,id',
        ];
    }
}
