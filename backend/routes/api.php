<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('produtos', ProductController::class)
        ->parameters([
            'produtos' => 'product'
        ])->missing(function () {
            return response()->json(['message' => 'Produto não encontrado'], Response::HTTP_NOT_FOUND);
        });

    Route::apiResource('compras', PurchaseController::class)
        ->parameters([
            'compras' => 'purchase'
        ])->missing(function () {
            return response()->json(['message' => 'Produto não encontrado'], Response::HTTP_NOT_FOUND);
        })->except(['update']);
});
