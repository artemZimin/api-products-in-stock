<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\ReleaseProductsReserveContract;
use App\Contracts\Actions\ReleaseProductsReserveInStockContract;
use App\Http\Requests\ReleaseProductsReserveRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ReleaseProductsReserveController extends Controller
{
    public function __construct(
        private readonly ReleaseProductsReserveContract $releaseProductsReserveAction,
        private readonly ReleaseProductsReserveInStockContract $releaseProductsReserveInStockAction
    ) {
    }

    /**
     * @param ReleaseProductsReserveRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(ReleaseProductsReserveRequest $request): JsonResponse
    {
        $products = $request->getProducts();
        $stockId = $request->getStockId();

        if ($stockId === null) {
            $this->releaseProductsReserveAction->handle($products);
        } else {
            $this->releaseProductsReserveInStockAction->handle($stockId, $products);
        }

        return Response::json([
            'updated' => count($products),
        ]);
    }
}
