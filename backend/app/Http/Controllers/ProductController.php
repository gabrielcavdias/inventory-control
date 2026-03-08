<?php

namespace App\Http\Controllers;

use App\DTOs\ProductDTO;
use App\Http\Requests\StoreOrUpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use App\Services\ProductService;
use App\Traits\ApiResponses;
use Throwable;

class ProductController extends Controller
{
    use ApiResponses;
    public function __construct(private ProductService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->query('page', 1);
        $perPage = request()->query('per_page', 10);
        try {
            $paginatedResult = $this->service->getAllPaginated($perPage, $page);
            return new ProductCollection($paginatedResult); // 200 OK!
        } catch (Throwable $e) {
            logger("Erro ao listar produtos " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao listar produtos, tente novamente mais tarde.");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrUpdateProductRequest $request)
    {
        try {
            $productDTO = ProductDTO::createFromRequest($request);
            $productModel = $this->service->storeProduct($productDTO);
            return $this->created($productModel->toResource());
        } catch (Throwable $e) {
            logger("Erro ao criar produto " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao criar produto, tente novamente mais tarde.");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $this->ok($product->toResource());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreOrUpdateProductRequest $request, Product $product)
    {

        try {
            $productDTO = ProductDTO::createFromRequest($request);
            $productModel = $this->service->updateProduct($product, $productDTO);
            return $this->ok($productModel->toResource());
        } catch (Throwable $e) {
            logger("Erro ao atualizar produto " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao atualizar produto, tente novamente mais tarde.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $this->service->destroyProduct($product);
            return $this->ok(['message' => "Produto {$product->name} excluido com sucesso"]);
        } catch (Throwable $e) {
            logger("Erro ao excluir produt " . $e->getMessage());
            return $this->errorResponse(['message' => "Erro ao excluir produto {$product->name} tente novamente mais tarde."]);
        }
    }
}
