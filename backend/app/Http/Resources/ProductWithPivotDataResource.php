<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductWithPivotDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = [];
        foreach ($this->resource as $item) {
            $products[] = [
                'id' => $item['id'],
                'nome' => $item['name'],
                'quantidade' => $item['pivot']['quantity'],
                'preco_unitario' => $item['pivot']['unit_price'] / 100
            ];
        }
        return $products;
    }
}
