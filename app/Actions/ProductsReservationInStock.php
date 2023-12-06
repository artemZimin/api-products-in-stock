<?php

namespace App\Actions;

use App\Contracts\Actions\AtomicLocksContract;
use App\Contracts\Actions\FetchAvailableStockByIdContract;
use App\Contracts\Actions\FetchProductByStockAndProductCodeContract;
use App\Contracts\Actions\ProductReservationContract;
use App\Contracts\Actions\ProductsReservationInStockContract;
use App\Contracts\Repository\ProductRepositoryContract;
use Illuminate\Support\Facades\Cache;

class ProductsReservationInStock implements ProductsReservationInStockContract
{
    public function __construct(
        private readonly FetchAvailableStockByIdContract $fetchAvailableStockById,
        private readonly ProductRepositoryContract $productRepository,
        private readonly FetchProductByStockAndProductCodeContract $fetchProductByStockAndProductCodeAction,
        private readonly ProductReservationContract $productReservationAction,
        private readonly AtomicLocksContract $atomicLocks
    ) {
    }

    public function handle(array $products, int $stockId): int
    {
        $stock = $this->fetchAvailableStockById->handle($stockId);

        $this->atomicLocks->handle(function (&$locks) use ($products, $stock) {
            $this->productRepository->transaction(function () use ($products, $stock, &$locks) {
                foreach ($products as $product) {
                    $lock = Cache::lock(sprintf('products_%d', $product->getCode()));
                    $locks[] = $lock;

                    $lock->block(10);

                    $productDTO = $this->fetchProductByStockAndProductCodeAction->handle(
                        $stock,
                        $product->getCode()
                    );

                    $this->productReservationAction->handle($productDTO, $product);
                }
            });
        });

        return count($products);
    }
}
