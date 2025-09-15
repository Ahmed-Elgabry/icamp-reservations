<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quantity_to_discount' => 'sometimes|required|integer|min:1',
            'correct_quantity' => 'sometimes|required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment,stockTaking_decrement,stockTaking_increment',
            'reason' => 'nullable|string|max:255',
            'verified' => 'nullable|in:0,1',
            'percentage' => 'nullable|string|max:255',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ];
    }
}
