<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;

class ProductService
{
    public function getAllPaginated(int $perPage = 10, int $page = 1, string $name = '')
    {
        return Product::query()->when(!empty($name), fn($q) => $q->whereLike('name', "%$name%"))->paginate(perPage: $perPage, page: $page);
    }

    public function storeProduct(ProductDTO $productData): Product
    {
        $createdProduct = Product::query()->create($productData->toArray());
        // Seta os valores que já são default, mas não retornados no create
        $createdProduct->average_cost = 0;
        $createdProduct->stock_quantity = 0;
        return $createdProduct;
    }

    public function updateProduct(Product $product, ProductDTO $productData): Product
    {
        $product->update($productData->toArray());
        return $product->fresh();
    }

    public function destroyProduct(Product $product): bool|null
    {
        //  TODO: verificar se existem models relacionadas etc e tal
        return $product->delete();
    }
}
