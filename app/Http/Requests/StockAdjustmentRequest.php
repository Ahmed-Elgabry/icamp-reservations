<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockAdjustmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'stock_id' => 'required|exists:stocks,id',
            'quantity_to_discount' => 'sometimes|required|integer|min:1',
            'correct_quantity' => 'sometimes|required|integer|min:1',
            'type' => 'required|in:item_decrement,item_increment,stockTaking_decrement,stockTaking_increment',
            'reason' => 'nullable|string|max:255',
            'percentage' => 'nullable|string|max:255',
            'verified' => 'nullable|in:0,1',
            'source' => 'nullable',
            'custom_reason' => 'nullable|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'note' => 'nullable|string',
            'employee_name' => 'nullable|string|max:255',
            'date_time' => 'nullable|date',
            'image' => 'nullable|image|max:20480',
        ];
    }
}
