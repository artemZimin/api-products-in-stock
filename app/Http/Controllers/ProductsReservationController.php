<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\ProductsReservationContract;
use App\Contracts\Actions\ProductsReservationInStockContract;
use App\Http\Requests\ProductsReservationRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ProductsReservationController extends Controller
{
    public function __construct(
        private readonly ProductsReservationContract $productsReservationAction,
        private readonly ProductsReservationInStockContract $productsReservationInStockAction
    ) {
    }

    /**
     * @param ProductsReservationRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(ProductsReservationRequest $request): JsonResponse
    {
        $products = $request->getProducts();
        $stockId = $request->getStockId();

        if ($stockId === null) {
            $this->productsReservationAction->handle($request->getProducts());
        } else {
            $this->productsReservationInStockAction->handle($products, $stockId);
        }

        return Response::json([
            'updated' => count($products)
        ]);
    }
}
