<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'vendas_hoje' => $this['sales_count'],
            'lucro_hoje' => $this['total_profit'] / 100,
            'total_gasto' => $this['total_cost_spent'] / 100,
            'itens_vendidos' => $this['items_sold_count'],
            'produtos' => new ProductCollection($this['products']),
            'vendas' => new SaleCollection($this['sales']),
            'compras' => $this->parsePurchases($this['purchases'])
        ];
    }

    /**
     * Não é a compra em si, mas sim um dado calculado
     * por isso não passamos para um PurchaseCollection por exemplo.
     */
    private function parsePurchases(array $purchases)
    {
        $newPurchases = [];
        foreach ($purchases as $purchase) {
            $newPurchases[] = [
                'fornecedor' => $purchase['supplier'],
                'custo_total' => $purchase['total_cost'] / 100,
            ];
        }
        return $newPurchases;
    }
}
