<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehousRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'material_id' => 'required',
            'remainder' => 'required',
            'price' => 'required'
        ];
    }
}
