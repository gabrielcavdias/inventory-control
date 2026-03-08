<?php

namespace App\DTOs;

use App\Http\Requests\StorePurchaseRequest;

class PurchaseDTO
{
    public function __construct(
        public string $supplier,
        public array $products,
    ) {}

    public static function createFromRequest(StorePurchaseRequest $request)
    {
        // $table->integer('unit_price');
        // $table->integer('quantity');
        $data = $request->validated();
        $productsParsed = array_map(fn(array $product) => [
            'product_id' => $product['id'],
            'unit_price' => +$product['preco_unitario'] * 100,
            'quantity' => $product['quantidade']

        ], $data['produtos']);
        return new self(
            supplier: $data['fornecedor'],
            products: $productsParsed,
        );
    }
}
