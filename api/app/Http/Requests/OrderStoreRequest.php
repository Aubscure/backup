<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_first_name' => 'required|string',
            'customer_last_name' => 'required|string',
            'customer_id_number' => 'required|string',
            'total_price' => 'required|numeric',
            'payment_method' => 'nullable|string|in:cashier,credit_card',
            'order_status' => 'nullable|string|in:pending,processing,completed',
            'order_details' => 'required|array',
            'order_details.*.product_id' => 'required|integer|exists:products,id',
            'order_details.*.quantity' => 'required|integer',
            'order_details.*.unit_price' => 'required|numeric',
            'order_details.*.total' => 'required|numeric',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$this->input('payment_method')) {
                $this->merge(['payment_method' => 'cashier']);
            }
            if (!$this->input('order_status')) {
                $this->merge(['order_status' => 'pending']);
            }
        });
    }
}
