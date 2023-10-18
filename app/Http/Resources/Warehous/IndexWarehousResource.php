<?php

namespace App\Http\Resources\Warehous;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexWarehousResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'warehous_id' => $this->warehous_id,
            'material_name' => $this->material_name,
            'remainder' => $this->remainder,
            'price' => $this->price
        ];
    }
}
