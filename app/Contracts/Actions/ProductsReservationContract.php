<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductForReservationDTO;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundException;

interface ProductsReservationContract
{
    public function __construct(
        ProductRepositoryContract $productRepository,
        ProductReservationContract $productReservationAction,
        AtomicLocksContract $atomicLocks
    );

    /**
     * Резервирует товары
     *
     * @param ProductForReservationDTO[] $products
     * @return void
     * @throws DomainException
     * @throws NotFoundException
     */
    public function handle(array $products): void;
}
