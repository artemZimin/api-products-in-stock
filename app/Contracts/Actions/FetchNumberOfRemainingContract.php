<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;

interface FetchNumberOfRemainingContract
{
    public function __construct(ProductRepositoryContract $productRepository);

    public function handle(): int;
}
