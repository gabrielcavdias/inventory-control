<?php

namespace App\DTOs;

use App\Http\Requests\StoreSaleRequest;
use App\Interfaces\TransactionInterface;

class SaleDTO implements TransactionInterface
{
    public function __construct(
        public string $customer,
        public TransactionProductList $products,
    ) {}

    public static function createFromRequest(StoreSaleRequest $request)
    {
        $data = $request->validated();
        $productsParsed = array_map(fn(array $product) => [
            'product_id' => $product['id'],
            'unit_price' => +$product['preco_unitario'] * 100,
            'quantity' => $product['quantidade']

        ], $data['produtos']);
        return new self(
            customer: $data['cliente'],
            products: new TransactionProductList($productsParsed),
        );
    }
}
