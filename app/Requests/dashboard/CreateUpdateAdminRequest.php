<?php

namespace App\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateAdminRequest extends FormRequest
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

        if($this->getMethod() === 'PUT' || $this->getMethod() ===  'PATCH'){
            return [
                'name'                   => 'required|string|max:191',
                'phone'                  => 'nullable|unique:users,phone,'.$this->id,
                'email'                  => 'required|email|unique:users,email,'.$this->id,
                'lang'                  => 'required',
                'image'                  => 'nullable',
                'is_active'              => 'nullable',
                'password'              => 'nullable',
            ];

            }else{
                return [
                    'name'                   => 'required|string|max:191',
                    'phone'                  => 'nullable|unique:users,phone',
                    'email'                  => 'required|email|unique:users,email',
                    'lang'                  => 'required',
                    'image'                  => 'nullable',
                    'is_active'              => 'nullable',
                    'password'              => 'required',
                ];
            }
    }
}
