<?php

namespace App\Http\Controllers;

use App\DTOs\SaleDTO;
use App\Exceptions\NotEnoughProductsException;
use App\Exceptions\ProductMismatchException;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Resources\SaleCollection;
use App\Http\Resources\SaleShowResource;
use App\Models\Sale;
use App\Services\SaleService;
use App\Traits\ApiResponses;
use Throwable;

class SaleController extends Controller
{
    use ApiResponses;
    public function __construct(private SaleService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->query('page', 1);
        $perPage = request()->query('per_page', 10);
        try {
            $items = $this->service->getAllPaginated($page, $perPage);
            return new SaleCollection($items);
        } catch (Throwable $e) {
            logger("Erro ao buscar compras " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao buscar compras, tente novamente mais tarde.");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        try {
            $saleDTO = SaleDTO::createFromRequest($request);
            return $this->ok($this->service->createNewSale($saleDTO));
        } catch (ProductMismatchException | NotEnoughProductsException $e) {
            return $this->unprocessable(data: ['errors' => ['produtos' => $e->getMessage()]], message: $e->getMessage());
        } catch (Throwable $e) {
            logger("Erro ao venda compra " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao registrar venda, tente novamente mais tarde.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $sale)
    {
        try {
            $saleItem = $this->service->findSaleWithProducts($sale);
            return new SaleShowResource($saleItem);
        } catch (Throwable $e) {
            logger("Erro ao encontrar venda " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao encontrar venda, tente novamente mais tarde.");
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $sale)
    {
        $this->service->cancelSale($sale);
    }
}
