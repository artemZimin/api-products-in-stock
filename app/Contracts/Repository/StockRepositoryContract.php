<?php

namespace App\Contracts\Repository;

use App\DTO\StockDTO;

interface StockRepositoryContract extends RepositoryContract
{
    /**
     * Получает склад по id
     *
     * @param int $id
     * @return StockDTO|null
     */
    public function fetchStockById(int $id): ?StockDTO;
}
