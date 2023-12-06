<?php

namespace App\Actions;

use App\Contracts\Actions\ProductReservationContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\ProductForReservationDTO;
use App\DTO\ProductUpdateQuantityDTO;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductReservation implements ProductReservationContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository
    ) {
    }

    public function handle(
        ProductDTO $productDTO,
        ProductForReservationDTO $productForReservationDTO
    ): void {
        if (($productDTO->getQuantity() - $productForReservationDTO->getCount()) < 0) {
            throw DomainException::create(
                sprintf(
                    'Invalid quantity of products (product code: %d, available: %d)',
                    $productDTO->getCode(),
                    $productDTO->getQuantity()
                ),
                400
            );
        }

        $productToUpdate = new ProductUpdateQuantityDTO(
            $productDTO->getId(),
            $productDTO->getQuantity() - $productForReservationDTO->getCount(),
            $productDTO->getQuantityInReserve() + $productForReservationDTO->getCount()
        );

        try {
            $this->productRepository->updateQuantityAndQuantityInReserve($productToUpdate);
        } catch (ModelNotFoundException) {
            throw NotFoundException::create(sprintf('Product %d', $productDTO->getId()));
        }
    }
}
