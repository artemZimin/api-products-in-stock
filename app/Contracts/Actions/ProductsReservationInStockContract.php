<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductForReservationDTO;
use App\Exceptions\NotAvailableException;
use App\Exceptions\NotFoundException;

interface ProductsReservationInStockContract
{
    public function __construct(
        FetchAvailableStockByIdContract $fetchAvailableStockById,
        ProductRepositoryContract $productRepository,
        FetchProductByStockAndProductCodeContract $fetchProductByStockAndProductCodeAction,
        ProductReservationContract $productReservationAction,
        AtomicLocksContract $atomicLocks
    );

    /**
     * Резервирует товары в рамках склада
     *
     * @param ProductForReservationDTO[] $products
     * @param int $stockId
     * @return int
     * @throws NotAvailableException
     * @throws NotFoundException
     */
    public function handle(array $products, int $stockId): int;
}
