<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|unique:products|max:255',
            'code' => 'required'
        ];
    }
}
