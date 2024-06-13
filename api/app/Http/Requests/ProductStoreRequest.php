<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0', // Adjusted to allow numeric values, including decimals
            'photo' => 'required',
            'category' => 'required|in:HM,TM,IT',
            'sizes' => 'required|array',
            'sizes.*.size' => 'required|in:XSM,SM,M,L,XL,XXL',
            'sizes.*.quantity' => 'required|integer|min:0',
        ];
    }

}
