<?php

namespace App\Repository;

use App\Contracts\Repository\StockRepositoryContract;
use App\DTO\StockDTO;
use App\Models\Stock;

class StockRepository extends Repository implements StockRepositoryContract
{
    public function fetchStockById(int $id): ?StockDTO
    {
        /** @var Stock|null $stock */
        $stock = Stock::query()->find($id);

        return $stock?->toDTO();
    }
}
