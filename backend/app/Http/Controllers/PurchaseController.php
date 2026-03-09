<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use App\DTOs\PurchaseDTO;
use App\Http\Resources\PurchaseIndexCollection;
use App\Http\Resources\PurchaseResource;
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
        $page = request()->query('page', 1);
        $perPage = request()->query('per_page', 10);
        try {
            return PurchaseResource::collection($this->service->getAllPaginated(page: $page, perPage: $perPage)); // 200 ok
        } catch (Throwable $e) {
            logger("Erro ao buscar compras " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao buscar compras, tente novamente mais tarde.");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        try {
            $purchaseDTO = PurchaseDTO::createFromRequest($request);
            return $this->ok($this->service->createNewPurchase($purchaseDTO));
        } catch (Throwable $e) {
            logger("Erro ao registrar compra " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao registrar compra, tente novamente mais tarde.");
        }
    }
}
