<?php

namespace App\Actions;

use App\Contracts\Actions\ReleaseProductReserveByDTOContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\ProductUpdateQuantityDTO;

class ReleaseProductReserveByDTO implements ReleaseProductReserveByDTOContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
    ) {
    }

    public function handle(ProductDTO $productDTO): void
    {
        $productToUpdate = new ProductUpdateQuantityDTO(
            $productDTO->getId(),
            $productDTO->getQuantity() + $productDTO->getQuantityInReserve(),
            0
        );

        $this->productRepository->updateQuantityAndQuantityInReserve($productToUpdate);
    }
}
