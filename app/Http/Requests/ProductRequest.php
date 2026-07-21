<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('id');
        return [
            'name' => 'required|string|max:225',
            'code' => 'required|string|max:50|unique:products,code,' . $productId,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric',
        ];
    }
}
