<?php

namespace App\Http\Resources\Warehous;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderWarehousResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
