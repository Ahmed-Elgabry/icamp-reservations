<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderSignInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Only allow authenticated users to update order sign-in
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
 	'time_of_receipt' => 'nullable|date_format:H:i',
        'time_of_receipt_notes' => 'nullable|string|max:500',
        'delivery_time' => 'nullable|date_format:H:i',
        'delivery_time_notes' => 'nullable|string|max:500',
        'order_id' => 'required|exists:orders,id',
        'notes' => 'nullable|string|max:1000',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'audio' => 'nullable|file|mimes:mp3,wav,ogg,webm|max:51200', // 50MB
        'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:102400', // Reduced to 100MB
        'remove_photo' => 'nullable|boolean',
        'remove_audio' => 'nullable|boolean',
        'remove_video' => 'nullable|boolean',
        'existing_photo' => 'nullable|string',
        'existing_audio' => 'nullable|string',
        'existing_video' => 'nullable|string',
        ];
    }
    
    /**
     * Get custom messages for validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'photo.max' => 'The photo may not be greater than 2MB.',
            'audio.max' => 'The audio file may not be greater than 5MB.',
            'video.max' => 'The video file may not be greater than 10MB.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif.',
            'audio.mimes' => 'The audio must be a file of type: mp3, wav, ogg.',
            'video.mimes' => 'The video must be a file of type: mp4, mov, avi.',
        ];
    }
}
