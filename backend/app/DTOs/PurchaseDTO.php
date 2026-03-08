<?php

namespace App\DTOs;

use App\Http\Requests\StorePurchaseRequest;
use App\Interfaces\TransactionInterface;

class PurchaseDTO implements TransactionInterface
{
    public function __construct(
        public string $supplier,
        public TransactionProductList $products,
    ) {}

    public static function createFromRequest(StorePurchaseRequest $request)
    {
        $data = $request->validated();
        $productsParsed = array_map(fn(array $product) => [
            'product_id' => $product['id'],
            'unit_price' => +$product['preco_unitario'] * 100,
            'quantity' => $product['quantidade']

        ], $data['produtos']);
        return new self(
            supplier: $data['fornecedor'],
            products: new TransactionProductList($productsParsed),
        );
    }
}
