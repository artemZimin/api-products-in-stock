<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;

interface ReleaseProductReserveByDTOContract
{
    public function __construct(ProductRepositoryContract $productRepository);

    /**
     * Освобождает резерв товара
     *
     * @param ProductDTO $productDTO
     * @return void
     */
    public function handle(ProductDTO $productDTO): void;
}
