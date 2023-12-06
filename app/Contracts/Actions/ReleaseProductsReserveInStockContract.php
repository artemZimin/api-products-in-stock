<?php

namespace App\Contracts\Actions;

use App\Actions\AtomicLocks;
use App\Exceptions\NotAvailableException;
use App\Exceptions\NotFoundException;
use App\Repository\ProductRepository;

interface ReleaseProductsReserveInStockContract
{
    public function __construct(
        FetchAvailableStockByIdContract $fetchAvailableStockByIdAction,
        FetchProductByStockAndProductCodeContract $fetchProductByStockAndProductCodeAction,
        ProductRepository $productRepository,
        AtomicLocks $atomicLocksAction,
        ReleaseProductReserveByDTOContract $releaseProductReserveByDTOAction
    );

    /**
     * Резервирование товаров в рамках склада
     *
     * @param int $stockId
     * @param int[] $products
     * @return int
     * @throws NotAvailableException
     * @throws NotFoundException
     */
    public function handle(int $stockId, array $products): int;
}
