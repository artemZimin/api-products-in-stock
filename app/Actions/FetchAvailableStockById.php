<?php

namespace App\Actions;

use App\Contracts\Actions\FetchAvailableStockByIdContract;
use App\Contracts\Repository\StockRepositoryContract;
use App\DTO\StockDTO;
use App\Exceptions\NotAvailableException;
use App\Exceptions\NotFoundException;

class FetchAvailableStockById implements FetchAvailableStockByIdContract
{
    public function __construct(
        private readonly StockRepositoryContract $stockRepository
    ) {
    }

    public function handle(int $stockId): StockDTO
    {
        $stock = $this->stockRepository->fetchStockById($stockId);

        $stockName = sprintf('Stock %d', $stockId);

        if ($stock === null) {
            throw NotFoundException::create($stockName);
        }

        if (!$stock->getAvailable()) {
            throw NotAvailableException::create($stockName);
        }

        return $stock;
    }
}
