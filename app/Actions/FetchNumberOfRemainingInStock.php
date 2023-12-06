<?php

namespace App\Actions;

use App\Contracts\Actions\FetchNumberOfRemainingInStockContract;
use App\Contracts\Actions\FetchAvailableStockByIdContract;
use App\Contracts\Repository\ProductRepositoryContract;
use Illuminate\Support\Facades\Cache;

class FetchNumberOfRemainingInStock implements FetchNumberOfRemainingInStockContract
{
    public function __construct(
        private readonly ProductRepositoryContract $productRepository,
        private readonly FetchAvailableStockByIdContract $fetchStockByIdAction
    ) {
    }

    public function handle(int $stockId): int
    {
        $lock = Cache::lock(sprintf('stock_%d', $stockId), 10);

        return (int)$lock->get(function () use ($stockId) {
            $stock = $this->fetchStockByIdAction->handle($stockId);

            return $this->productRepository->fetchNumberOfRemainingInStock(
                $stock->getId()
            );
        });
    }
}
