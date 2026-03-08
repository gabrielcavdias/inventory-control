<?php

namespace App\DTOs;

use ArrayIterator;
use DomainException;
use IteratorAggregate;
use Traversable;

/**
 * Essa classe serve para validar a estrutura da lista de produtos
 * para que possa ser usado em uma interface que garanta
 * que cada uma das chaves exista
 * Seria o equivalente a array<product_id:int, unit_price: int, quantity:int>[] em uma linguagem com generics
 */
class TransactionProductList implements IteratorAggregate
{
    public array $items;
    public function __construct(array $products)
    {
        foreach ($products as $product) {
            if (! is_array($product)) {
                throw new DomainException("A lista de produtos de uma transação deve ser um array");
            }
            $hasAllKeys = array_all(['product_id', 'unit_price', 'quantity'], fn(string $key) => array_key_exists($key, $product) && gettype($product[$key] == 'integer'));
            if (! $hasAllKeys) {
                throw new DomainException("A lista de produtos de uma transação deve ter todas as chaves");
            }
        }
        $this->items = $products;
    }


    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
