<?php

namespace App\Actions;

use App\Contracts\Actions\FetchProductByStockAndProductCodeContract;
use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\StockDTO;
use App\Exceptions\NotFoundException;

class FetchProductByStockAndProductCode implements FetchProductByStockAndProductCodeContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository
    ) {
    }

    public function handle(StockDTO $stockDTO, int $productCode): ProductDTO
    {
        $productDTO = $this->productRepository->fetchOneByStockAndProductCode(
            $stockDTO,
            $productCode
        );

        if ($productDTO === null) {
            throw NotFoundException::create(
                sprintf('Product %d', $productCode)
            );
        }

        return $productDTO;
    }
}
