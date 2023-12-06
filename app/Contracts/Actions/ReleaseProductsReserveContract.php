<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;

interface ReleaseProductsReserveContract
{
    public function __construct(
        ProductRepositoryContract $productRepository,
        AtomicLocksContract $atomicLocksAction,
        ReleaseProductReserveByDTOContract $releaseProductReserveByDTOAction
    );


    /**
     * Освобождает резерв товаров
     *
     * @param array $products
     * @return void
     */
    public function handle(array $products): void;
}
