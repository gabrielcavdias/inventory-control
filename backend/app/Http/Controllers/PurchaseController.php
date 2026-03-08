<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\DTOs\PurchaseDTO;
use App\Services\PurchaseService;
use App\Traits\ApiResponses;
use Throwable;

class PurchaseController extends Controller
{
    use ApiResponses;
    public function __construct(private PurchaseService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd("TODO: implementar");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        // try {
        $purchaseDTO = PurchaseDTO::createFromRequest($request);
        return $this->ok($this->service->createNewPurchase($purchaseDTO));
        // } catch (Throwable $e) {
        // logger("Erro ao registrar compra " . $e->getMessage());
        // return $this->errorResponse(message: "Erro ao registrar compra, tente novamente mais tarde.");
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
