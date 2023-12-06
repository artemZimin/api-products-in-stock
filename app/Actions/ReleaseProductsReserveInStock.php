<?php

namespace App\Actions;

use App\Contracts\Actions\AtomicLocksContract;
use App\Contracts\Actions\FetchAvailableStockByIdContract;
use App\Contracts\Actions\FetchProductByStockAndProductCodeContract;
use App\Contracts\Actions\ReleaseProductReserveByDTOContract;
use App\Contracts\Actions\ReleaseProductsReserveInStockContract;
use App\Contracts\Repository\ProductRepositoryContract;
use Illuminate\Support\Facades\Cache;

class ReleaseProductsReserveInStock implements ReleaseProductsReserveInStockContract
{
    public function __construct(
        private readonly FetchAvailableStockByIdContract  $fetchAvailableStockByIdAction,
        private readonly FetchProductByStockAndProductCodeContract $fetchProductByStockAndProductCodeAction,
        private readonly ProductRepositoryContract $productRepository,
        private readonly AtomicLocksContract $atomicLocksAction,
        private readonly ReleaseProductReserveByDTOContract $releaseProductReserveByDTOAction
    ) {
    }

    public function handle(int $stockId, array $products): int
    {
        $stock = $this->fetchAvailableStockByIdAction->handle($stockId);

        $this->atomicLocksAction->handle(function (&$locks) use ($products, $stock) {
            $this->productRepository->transaction(function () use (&$locks, $products, $stock) {
                foreach ($products as $productCode) {
                    $lock = Cache::lock(sprintf('products_%d', $productCode));
                    $locks[] = $lock;

                    $lock->block(10);

                    $productDTO = $this->fetchProductByStockAndProductCodeAction->handle(
                        $stock,
                        (int)$productCode
                    );

                    $this->releaseProductReserveByDTOAction->handle($productDTO);
                }
            });
        });

        return count($products);
    }
}
