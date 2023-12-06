<?php

namespace App\Actions;

use App\Contracts\Actions\AtomicLocksContract;
use App\Contracts\Actions\ProductReservationContract;
use App\Contracts\Actions\ProductsReservationContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\ProductForReservationDTO;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundException;
use Illuminate\Support\Facades\Cache;

class ProductsReservation implements ProductsReservationContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly ProductReservationContract $productReservationAction,
        private readonly AtomicLocksContract $atomicLocks
    ) {
    }

    public function handle(array $products): void
    {
        $this->atomicLocks->handle(function (&$locks) use ($products) {
            $this->productRepository->transaction(function () use ($products, &$locks) {
                foreach ($products as $product) {
                    $lock = Cache::lock(sprintf('products_%d', $product->getCode()));
                    $locks[] = $lock;

                    $reserveCount = $product->getCount();

                    $lock->block(10);

                    $productDTO = $this->productRepository->fetchOneByProductCode($product->getCode());

                    if (!$productDTO) {
                        throw NotFoundException::create(sprintf('Product %d', $product->getCode()));
                    }

                    $availableQuantity = $this->productRepository->fetchNumberOfRemainingByCode($product->getCode());

                    if ($reserveCount > $availableQuantity) {
                        throw DomainException::create(
                            sprintf(
                                'Available quantity for product %d: %d, %d given',
                                $product->getCode(),
                                $availableQuantity,
                                $reserveCount
                            ),
                            400
                        );
                    }

                    $this->productRepository->fetchByProductCodeWhereStockIsAvailable(
                        $product->getCode(),
                        function (ProductDTO $productDTO) use (&$reserveCount) {
                            $toReserve = min($reserveCount, $productDTO->getQuantity());

                            $productForReservationDTO = new ProductForReservationDTO(
                                $productDTO->getCode(),
                                $toReserve
                            );

                            $this->productReservationAction->handle($productDTO, $productForReservationDTO);

                            $reserveCount -= $toReserve;
                        }
                    );
                }
            });
        });
    }
}
