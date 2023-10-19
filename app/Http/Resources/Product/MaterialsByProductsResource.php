<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialsByProductsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'product_material_id' => $this->product_material_id,
            'material_name' => $this->material_name,
            'quantity' => $this->quantity
        ];
    }
}
