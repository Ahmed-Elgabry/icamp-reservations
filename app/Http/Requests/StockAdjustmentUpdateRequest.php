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
            'quantity_to_discount' => 'sometimes|required|integer',
            'correct_quantity' => 'sometimes|required|integer',
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
    /**
     * Remove any keys with null values before validation so the service
     * receives only meaningful fields.
     */
    protected function prepareForValidation()
    {
        $data = $this->all();
        $clean = [];
        foreach ($data as $k => $v) {
            if ($v !== null) {
                $clean[$k] = $v;
            }
        }
        $this->replace($clean);
    }

    
}
