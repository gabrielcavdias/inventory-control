<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductSale;
use App\Models\Purchase;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardService
{

    public function getDashboardData()
    {
        return Cache::remember('dashboard', now()->addMinute(), function () {
            $salesToday = Sale::query()->whereDate('created_at', Carbon::today())->get();
            $totalProfit = $salesToday->sum('profit');
            $salesCount = $salesToday->count();
            $itemsSoldCount = ProductSale::query()->select('quantity')->whereDate('created_at', Carbon::today())->get()->sum('quantity');

            $purchasesToday = ProductPurchase::query()->whereDate('created_at', Carbon::today())->get();
            $totalCostSpent = $purchasesToday->sum(fn(ProductPurchase $prPurchase) => $prPurchase->quantity * $prPurchase->unit_price);

            $products = Product::query()->latest()->limit(10)->get();

            $sales = $salesToday->filter(fn($_, $index) => $index < 4);
            $purchases = Purchase::query()->whereDate('created_at', Carbon::today())->with('products')->get()->map(function (Purchase $purchase) {
                return [
                    'supplier' => $purchase->supplier,
                    'total_cost' => $purchase->products->sum(fn(Product $product) => $product->pivot->quantity * $product->pivot->unit_price)
                ];
            })->toArray();
            return [
                'total_profit' => $totalProfit,
                'sales_count' => $salesCount,
                'items_sold_count' => $itemsSoldCount,
                'total_cost_spent' => $totalCostSpent,
                'products' => $products,
                'sales' => $sales,
                'purchases' => $purchases
            ];
        });
    }
}
