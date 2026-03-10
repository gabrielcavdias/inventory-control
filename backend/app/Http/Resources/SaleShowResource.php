<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cliente' => $this->customer,
            'lucro' => $this->profit / 100,
            'produtos' => new ProductWithPivotDataResource($this['products']->toArray()),
            'cancelada' => $this->cancelled_at,
        ];
    }
}
