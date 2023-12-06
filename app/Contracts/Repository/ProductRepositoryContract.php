<?php

namespace App\Contracts\Repository;

use App\DTO\ProductDTO;
use App\DTO\ProductUpdateQuantityDTO;
use App\DTO\StockDTO;
use Exception;

interface ProductRepositoryContract extends RepositoryContract
{
    /**
     * Получение количества остатка товаров на всех доступных складах
     *
     * @return int
     */
    public function fetchNumberOfRemaining(): int;

    /**
     * Получение количества остатка товара на всех доступных складах
     *
     * @param int $productCode
     * @return int
     */
    public function fetchNumberOfRemainingByCode(int $productCode): int;

    /**
     * Получение количества остатка товаров на складе
     *
     * @param int $stockId
     * @return int
     */
    public function fetchNumberOfRemainingInStock(int $stockId): int;

    /**
     * Получает товар по складу и коду товара
     *
     * @param StockDTO $stockDTO
     * @param int $code
     * @return ProductDTO|null
     * @throws Exception
     */
    public function fetchOneByStockAndProductCode(
        StockDTO $stockDTO,
        int $code
    ): ?ProductDTO;

    /**
     * Обновляет количество и количество зарезервированного товара
     *
     * @param ProductUpdateQuantityDTO $productUpdateQuantityDTO
     * @return void
     */
    public function updateQuantityAndQuantityInReserve(
        ProductUpdateQuantityDTO $productUpdateQuantityDTO
    ): void;

    /**
     * Получает товары по коду из доступных складов
     *
     * @param int $productCode
     * @param callable $process
     * @return void
     * @throws Exception
     */
    public function fetchByProductCodeWhereStockIsAvailable(int $productCode, callable $process): void;

    /**
     * Получает первый найденный товар по коду
     *
     * @param int $code
     * @return ProductDTO|null
     * @throws Exception
     */
    public function fetchOneByProductCode(int $code): ?ProductDTO;
}
