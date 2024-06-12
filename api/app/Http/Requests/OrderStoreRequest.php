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
            'payment_method' => 'nullable|string|in:cashier,credit_card', // Update with accepted values
            'order_status' => 'nullable|string|in:pending,processing,completed', // Update with accepted values
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
