<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardResource;
use App\Services\DashboardService;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Throwable;

class DashboardController extends Controller
{
    use ApiResponses;

    public function __construct(
        private DashboardService $service
    ) {}

    public function index()
    {
        try {
            return new DashboardResource($this->service->getDashboardData()); // 200 ok
        } catch (Throwable $e) {
            logger("Erro ao buscar dados do dashboard " . $e->getMessage());
            return $this->errorResponse(message: "Erro ao buscar dados do dashboard, tente novamente mais tarde.");
        }
    }
}
