<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'authenticate'])->middleware([HandlePrecognitiveRequests::class])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::apiResource('produtos', ProductController::class)
        ->parameters([
            'produtos' => 'product'
        ])->missing(function () {
            return response()->json(['message' => 'Produto não encontrado'], Response::HTTP_NOT_FOUND);
        })
        ->middleware([HandlePrecognitiveRequests::class]);

    Route::apiResource('compras', PurchaseController::class)
        ->parameters([
            'compras' => 'purchase'
        ])->missing(function () {
            return response()->json(['message' => 'Compra não encontrado'], Response::HTTP_NOT_FOUND);
        })->except(['update', 'destroy'])
        ->middleware([HandlePrecognitiveRequests::class]);

    Route::apiResource('vendas', SaleController::class)
        ->parameters([
            'compras' => 'sale'
        ])->missing(function () {
            return response()->json(['message' => 'Venda não encontrado'], Response::HTTP_NOT_FOUND);
        })->except(['update'])
        ->middleware([HandlePrecognitiveRequests::class]);
});
