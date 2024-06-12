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
            'price' => 'required|numeric',
            'photo' => 'nullable',
            'category' => 'required|in:HM,TM,IT',
            'sizes' => 'nullable|array', // Validate that sizes is an array
            'sizes.*.size' => 'required|in:XSM,SM,M,L,XL,XXL', // Validate each size
            'sizes.*.quantity' => 'required|integer|min:0', // Validate each quantity
        ];
    }
}
