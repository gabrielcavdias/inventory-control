<?php

namespace App\Traits;

use App\Interfaces\TransactionInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

trait ProductCalculation
{

    private function updateProductStockQuantities(TransactionInterface $transactionData, string $operator = '+'): void
    {
        if (! in_array($operator, ['+', '-'])) {
            throw new DomainException("Os operadores devem ser +(mais) ou -(menos)");
        }
        /**
         * Aqui a ideia é atualizar cada quantidade em uma única query
         * caso os produtos fossem muitos, poderíamos fazer por batches
         * usando array_chunks e afins, mas para simplificar estou assumindo que não serão tantos produtos comprados por vez.
         */
        $cases = [];
        $ids = [];
        $params = [];

        foreach ($transactionData->products as $productPurchase) {
            $cases[] = "WHEN id = ? THEN stock_quantity $operator ?";
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
}
