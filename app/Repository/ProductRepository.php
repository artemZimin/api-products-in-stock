<?php

namespace App\Repository;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\ProductUpdateQuantityDTO;
use App\DTO\StockDTO;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository implements ProductRepositoryContract
{
    public function fetchNumberOfRemaining(): int
    {
        /** @var Model $result */
        $result = Product::query()
            ->select(DB::raw('sum(quantity)'))
            ->whereRelation('stock', 'available', '=', true)
            ->first();

        return (int)$result->getAttribute('sum');
    }

    public function fetchNumberOfRemainingByCode(int $productCode): int
    {
        /** @var Model $result */
        $result = Product::query()
            ->select(DB::raw('sum(quantity)'))
            ->whereRelation('stock', 'available', '=', true)
            ->where('code', '=', $productCode)
            ->first();

        return (int)$result->getAttribute('sum');
    }

    public function fetchNumberOfRemainingInStock(int $stockId): int
    {
        /** @var Model $result */
        $result = Product::query()
            ->select(DB::raw('sum(quantity)'))
            ->groupBy('stock_id')
            ->having('stock_id', '=', $stockId)
            ->first();

        return (int)$result->getAttribute('sum');
    }

    public function fetchOneByProductCode(int $code): ?ProductDTO
    {
        /** @var Product $product */
        $product = Product::query()
            ->where('code', '=', $code)
            ->first();

        return $product?->toDTO();
    }

    public function fetchOneByStockAndProductCode(
        StockDTO $stockDTO,
        int $code,
    ): ?ProductDTO {
        /** @var Product|null $product */
        $product = Product::query()
            ->where('stock_id', '=', $stockDTO->getId())
            ->where('code', '=', $code)
            ->first();

        return $product?->toDTO();
    }

    public function fetchByProductCodeWhereStockIsAvailable(int $productCode, callable $process): void
    {
        Product::query()
            ->whereRelation('stock', 'available', '=', true)
            ->where('code', '=', $productCode)
            ->chunk(self::LIMIT_PER_QUERY, function (Collection $products) use ($process) {
                /** @var Product $product */
                foreach ($products as $product) {
                    $process($product->toDTO());
                }
            });
    }

    public function updateQuantityAndQuantityInReserve(
        ProductUpdateQuantityDTO $productUpdateQuantityDTO
    ): void {
        /** @var Product $product */
        $product = Product::query()->findOrFail($productUpdateQuantityDTO->getId());

        $product->setQuantity($productUpdateQuantityDTO->getQuantity());
        $product->setQuantityInReserve($productUpdateQuantityDTO->getQuantityInReserve());

        $product->save();
    }
}
