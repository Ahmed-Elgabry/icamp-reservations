<?php

namespace App\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class editProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image'      => 'nullable|image',
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email,'.auth()->user()->id,
            'phone'      => 'required|unique:users,phone,'.auth()->user()->id,
            'password'   => 'confirmed|min:8|nullable',
            'password_confirmation' => 'same:password|nullable',
        ];
    }
}
