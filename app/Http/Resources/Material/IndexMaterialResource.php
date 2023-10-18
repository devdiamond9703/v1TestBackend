<?php

namespace App\Http\Resources\Material;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexMaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
