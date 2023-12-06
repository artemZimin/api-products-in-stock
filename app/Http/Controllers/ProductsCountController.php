<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\FetchNumberOfRemainingContract;
use App\Contracts\Actions\FetchNumberOfRemainingInStockContract;
use App\Http\Requests\ProductsCountRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProductsCountController extends Controller
{
    public function __invoke(
        ProductsCountRequest $request,
        FetchNumberOfRemainingInStockContract $fetchNumberOfRemainingInStockAction,
        FetchNumberOfRemainingContract $fetchNumberOfRemainingAction
    ): JsonResponse {
        $stockId = $request->getStockId();

        $result = $stockId === null
            ? $fetchNumberOfRemainingAction->handle()
            : $fetchNumberOfRemainingInStockAction->handle($stockId);

        return Response::json([
            'result' => $result,
        ]);
    }
}
