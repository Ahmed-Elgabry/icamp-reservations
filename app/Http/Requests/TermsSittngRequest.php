<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Sanitizer;

class TermsSittngRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $data = $this->all();
        $data = Sanitizer::sanitizeArray($data, ['commercial_license_ar','commercial_license_en']);
        $this->merge($data);
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
