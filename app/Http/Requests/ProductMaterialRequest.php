<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductMaterialRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => 'required',
            'material_id' => 'required',
            'quantity' => 'required'
        ];
    }
}
