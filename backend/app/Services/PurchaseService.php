<?php

namespace App\Services;

use App\Exceptions\ProductMismatchException;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Purchase;
use App\DTOs\PurchaseDTO;
use Illuminate\Support\Facades\DB;

class PurchaseService
{

    public function createNewPurchase(PurchaseDTO $purchaseData)
    {
        return DB::transaction(function () use ($purchaseData) {
            $purchase = $this->createPurchaseModel($purchaseData);

            $productsIds = array_map(fn(array $product) => $product['product_id'], $purchaseData->products);

            $this->attachProductsToPurchase($purchaseData, $purchase, $productsIds);

            $this->updateProductStockQuantities($purchaseData);

            $this->updateProductAverageCosts($productsIds);

            return $purchase;
        });
    }

    private function createPurchaseModel(PurchaseDTO $purchaseData): Purchase
    {
        $purchase = new Purchase();
        $purchase->supplier = $purchaseData->supplier;
        $purchase->save();
        return $purchase->fresh();
    }

    private function attachProductsToPurchase(PurchaseDTO $purchaseData, Purchase $purchase, array $productsIds)
    {
        $productsCount = Product::query()->lockForUpdate()->whereIn('id', $productsIds)->count();
        if ($productsCount < count($purchaseData->products)) {
            throw new ProductMismatchException("Nem todos os produtos da compra estão disponíveis.");
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
        foreach ($purchaseData->products as $productPurchase) {
            $attachStatement[$productPurchase['product_id']] = [
                'unit_price' => $productPurchase['unit_price'],
                'quantity' => $productPurchase['quantity']
            ];
        }
        $purchase->products()->attach($attachStatement);
    }

    private function updateProductStockQuantities(PurchaseDTO $purchaseData): void
    {
        /**
         * Aqui a ideia é atualizar cada quantidade em uma única query
         * caso os produtos fossem muitos, poderíamos fazer por batches
         * usando array_chunks e afins, mas para simplificar estou assumindo que não serão tantos produtos comprados por vez.
         */
        $cases = [];
        $ids = [];
        $params = [];

        foreach ($purchaseData->products as $productPurchase) {
            $cases[] = "WHEN id = ? THEN stock_quantity + ?";
            $ids[] = $productPurchase['product_id'];
            $params[] = $productPurchase['product_id']; // when $productPurchase['product_id']
            $params[] = $productPurchase['quantity']; // then stock_quantity + $productPurchase['quantity']
        }

        $casesSql = implode(' ', $cases);
        $placeholders = count($ids) |> (fn($x) => array_fill(0, $x, '?')) |> (fn($y) => implode(',', $y));
        DB::update("
                UPDATE products
                SET stock_quantity = CASE {$casesSql} END
                WHERE id IN ({$placeholders})
            ", array_merge($params, $ids));
    }

    private function updateProductAverageCosts(array $productsIds)
    {
        $averages = ProductPurchase::query()
            ->selectRaw('
                        product_id,
                        SUM(unit_price * quantity) / SUM(quantity) as average_cost
                ')
            ->whereIn('product_id', $productsIds)
            ->groupBy('product_id')
            ->get();

        $cases = [];
        $ids = [];
        $params = [];

        foreach ($averages as $averageItem) {
            $cases[] = "WHEN id = ? THEN ?";
            $ids[] = $averageItem['product_id'];
            $params[] = $averageItem['product_id']; // when $averageItem['product_id']
            $params[] = $averageItem['average_cost']; // then $averageItem['average_cost']
        }

        $casesSql = implode(' ', $cases);
        $placeholders = count($ids) |> (fn($x) => array_fill(0, $x, '?')) |> (fn($y) => implode(',', $y));
        DB::update("
                UPDATE products
                SET average_cost = CASE {$casesSql} END
                WHERE id IN ({$placeholders})
            ", array_merge($params, $ids));
    }
}
