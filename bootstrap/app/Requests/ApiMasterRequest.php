<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class ApiMasterRequest extends FormRequest
{
    public function expectsJson()
    {
        return true;
    }

    public function messages()
    {
        return [
          
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'field' => $validator->errors()->keys()[0],
            'message' => $validator->errors()->first(),
            'status'  => false
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)); // 422
    }
}