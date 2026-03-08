<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Retornar: id, nome, custo_medio, preco_venda e estoque atual.
        return [
            'id' => $this->id,
            'nome' => $this->name,
            'preco_venda' => $this->suggested_price / 100, // De centavos para real com casas decimais
            'estoque_atual' => $this->stock_quantity,
            'custo_medio' => $this->average_cost,
        ];
    }
}
