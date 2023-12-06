<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\StockRepositoryContract;
use App\DTO\StockDTO;
use App\Exceptions\NotAvailableException;
use App\Exceptions\NotFoundException;

interface FetchAvailableStockByIdContract
{
    public function __construct(StockRepositoryContract $stockRepository);

    /**
     * Получение склада по его id
     *
     * Получает склад, или выбрасывает исключение
     * если склад недоступен или не найден
     *
     * @param int $stockId
     * @return StockDTO
     * @throws NotAvailableException
     * @throws NotFoundException
     */
    public function handle(int $stockId): StockDTO;
}
