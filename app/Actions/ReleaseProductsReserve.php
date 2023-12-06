<?php

namespace App\Actions;

use App\Contracts\Actions\AtomicLocksContract;
use App\Contracts\Actions\ReleaseProductsReserveContract;
use App\Contracts\Actions\ReleaseProductReserveByDTOContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Cache;

class ReleaseProductsReserve implements ReleaseProductsReserveContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly AtomicLocksContract $atomicLocksAction,
        private readonly ReleaseProductReserveByDTOContract $releaseProductReserveByDTOAction
    ) {
    }

    public function handle(array $products): void
    {
        $this->atomicLocksAction->handle(function (&$locks) use ($products) {
            $this->productRepository->transaction(function () use ($products, &$locks) {
                foreach ($products as $productCode) {
                    $lock = Cache::lock(sprintf('products_%d', $productCode));
                    $locks[] = $lock;

                    $lock->block(10);

                    $product = $this->productRepository->fetchOneByProductCode($productCode);

                    if (!$product) {
                        throw NotFoundException::create(sprintf('Product %d', $productCode));
                    }

                    $this->productRepository->fetchByProductCodeWhereStockIsAvailable(
                        $productCode,
                        function (ProductDTO $productDTO) {
                            $this->releaseProductReserveByDTOAction->handle($productDTO);
                        }
                    );
                }
            });
        });
    }
}
