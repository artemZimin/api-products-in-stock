<?php

namespace App\Actions;

use App\Contracts\Actions\FetchNumberOfRemainingContract;
use App\Contracts\Repository\ProductRepositoryContract;

class FetchNumberOfRemaining implements FetchNumberOfRemainingContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    public function handle(): int
    {
        return $this->productRepository->fetchNumberOfRemaining();
    }
}
