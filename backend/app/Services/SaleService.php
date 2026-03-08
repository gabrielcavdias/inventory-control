<?php

namespace App\Services;

use App\DTOs\SaleDTO;
use App\Exceptions\NotEnoughProductsException;
use App\Exceptions\ProductMismatchException;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\ProductCalculation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SaleService
{
    use ProductCalculation;
    public function createNewSale(SaleDTO $saleData)
    {
        return DB::transaction(function () use ($saleData) {
            $sale = $this->createSaleModel($saleData);

            $productsIds = array_map(fn(array $product) => $product['product_id'], $saleData->products->items);

            $products = Product::query()->lockForUpdate()->whereIn('id', $productsIds)->get();

            $this->attachProductsToSale($saleData, $sale, $products);

            $this->updateProductStockQuantities($saleData, '-');

            $sale->profit = $this->calculateProfit($saleData, $products);

            $sale->save();

            return $sale;
        });
    }


    private function createSaleModel(SaleDTO $saleData): Sale
    {
        $sale = new Sale();
        $sale->customer = $saleData->customer;
        $sale->profit = 0;
        $sale->save();
        return $sale->fresh();
    }


    private function attachProductsToSale(SaleDTO $saleData, Sale $sale, Collection $products)
    {
        $productsCount = count($products);
        if ($productsCount < count($saleData->products->items)) {
            throw new ProductMismatchException("Nem todos os produtos da venda estão disponíveis.");
        }
        foreach ($products as $product) {
            $intendedSaleItem = collect($saleData->products->items)->first(fn(array $item) => $item['product_id'] == $product->id);
            $intendedQuantity = data_get($intendedSaleItem, 'quantity', 0);
            if ($intendedQuantity > $product->stock_quantity) {
                throw new NotEnoughProductsException("$intendedQuantity é maior do que a quantidade disponível para o produto {$product->name}, a quantidade disponível é {$product->stock_quantity}");
            }
        }
        /**
         * O objetivo aqui é criar um array no formato que o attach do laravel espera
         * [3 => ['unit_price' => 20, 'quantity' => 5]
         * Sendo a chave do array o id do produto e o array os dados da tabela pivot
         */
        $attachStatement = [];
        /**
         * @var array{ id:string,unit_price:int,quantity:int }
         */
        foreach ($saleData->products as $productPurchase) {
            $attachStatement[$productPurchase['product_id']] = [
                'unit_price' => $productPurchase['unit_price'],
                'quantity' => $productPurchase['quantity']
            ];
        }
        $sale->products()->attach($attachStatement);
    }

    private function calculateProfit(SaleDTO $saleData, Collection $products)
    {
        $saleProducts = collect($saleData->products)->keyBy('product_id');
        $productCosts = $products->keyBy('id');

        $totalRevenue = 0;
        $totalCost = 0;

        foreach ($saleProducts as $productId => $saleProduct) {
            $quantity = $saleProduct['quantity'];
            $totalRevenue += $saleProduct['unit_price'] * $quantity;
            $totalCost += $productCosts[$productId]['average_cost'] * $quantity;
        }

        return $totalRevenue - $totalCost;
    }
}
