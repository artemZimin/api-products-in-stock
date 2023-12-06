<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;

interface FetchNumberOfRemainingInStockContract
{
    public function __construct(
        ProductRepositoryContract $productRepository,
        FetchAvailableStockByIdContract $fetchStockByIdAction
    );

    /**
     * Получение количества доступных к заказу товаров на складе
     *
     * @param int $stockId
     * @return int
     */
    public function handle(int $stockId): int;
}
