<?php

namespace App\Http\Controllers;

use App\DTOs\SaleDTO;
use App\Exceptions\NotEnoughProductsException;
use App\Exceptions\ProductMismatchException;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        // try {
        $saleDTO = SaleDTO::createFromRequest($request);
        return $this->ok($this->service->createNewSale($saleDTO));
        // } catch (ProductMismatchException | NotEnoughProductsException $e) {
        //     return $this->unprocessable(data: ['errors' => ['produtos' => $e->getMessage()]], message: $e->getMessage());
        // } catch (Throwable $e) {
        //     logger("Erro ao venda compra " . $e->getMessage());
        //     return $this->errorResponse(message: "Erro ao registrar venda, tente novamente mais tarde.");
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
