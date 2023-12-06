<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\StockDTO;
use App\Exceptions\NotFoundException;
use Exception;

interface FetchProductByStockAndProductCodeContract
{
    public function __construct(ProductRepositoryContract $productRepository);


    /**
     * Получение товара по складу и коду товара
     *
     * @param StockDTO $stockDTO
     * @param int $productCode
     * @return ProductDTO
     * @throws NotFoundException
     * @throws Exception
     */
    public function handle(StockDTO $stockDTO, int $productCode): ProductDTO;
}
