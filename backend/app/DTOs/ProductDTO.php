<?php

namespace App\DTOs;

use App\Http\Requests\StoreOrUpdateProductRequest;
use DomainException;

class ProductDTO
{
    public function __construct(
        public string $name,
        public int $suggested_price,
    ) {}

    public function toArray()
    {
        return get_object_vars($this);
    }

    public static function createFromRequest(StoreOrUpdateProductRequest $request)
    {
        $data = $request->validated();
        $data['preco_venda'] = +$data['preco_venda'];
        return new self(
            name: $data['nome'],
            suggested_price: $data['preco_venda'] * 100
        );
    }
}
