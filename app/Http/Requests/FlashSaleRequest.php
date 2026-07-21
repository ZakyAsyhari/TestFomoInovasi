<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FlashSaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $FlasShaleId = $this->route('id');
        return [
            'code' => 'required|string|max:50|unique:flashsales,code,' . $FlasShaleId,
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'qty' => 'required|numeric',
            'flashsale_price' => 'required|numeric',
        ];
    }
}
