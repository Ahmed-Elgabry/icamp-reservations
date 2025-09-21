<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255', 'unique:pages,url'],
            'is_available' => ['nullable', 'boolean'],
            'is_authenticated' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => __('dashboard.name_required'),
            'name.max' => __('dashboard.name_max_255'),
            'url.required' => __('dashboard.url_required'),
            'url.unique' => __('dashboard.url_unique'),
            'url.max' => __('dashboard.url_max_255'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_available' => $this->boolean('is_available'),
            'is_authenticated' => $this->boolean('is_authenticated'),
            'url' => $this->url ? strtok(strtok($this->url, '?'), '#') : $this->url,
        ]);
    }
}
