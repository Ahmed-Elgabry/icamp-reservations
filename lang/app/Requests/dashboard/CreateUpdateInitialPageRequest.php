<?php

namespace App\Requests\dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateInitialPageRequest extends FormRequest
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
                'desc.ar'         => 'required|string',
                'desc.en'         => 'required|string',
                'image'           => 'nullable',
                'order'           => 'required|numeric|unique:initial_pages,order,'.$this->initial_page,
                'is_active'       => 'required',

            ];

        }else{

            return [
                
                'desc.ar'         => 'required|string',
                'desc.en'         => 'required|string',
                'image'           => 'required',
                'order'           => 'required|numeric|unique:initial_pages,order',
                'is_active'       => 'required',

            ];
        }
    }
}
