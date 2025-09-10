<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationformRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'service_id'     => ['required','exists:services,id'],
            'booking_date'   => ['required','date','after_or_equal:today'],
            'time_slot'      => ['required', 'in:4-12,5-1,other'],
            'checkin_time'   => ['nullable','date_format:H:i','required_if:time_slot,other'],
            'checkout_time'  => ['nullable','date_format:H:i','required_if:time_slot,other'],
            'terms_accepted' => ['accepted'],
            'persons'        => ['required','integer','min:1','max:1000'],
            'first_name'     => ['required','string','max:255'],
            'last_name'      => ['required','string','max:255'],
            'mobile_phone'   => ['required','string','max:32'],
            'email'          => ['required','email','max:255'],
            'notes'          => ['nullable','string'],
        ];
    }
}
