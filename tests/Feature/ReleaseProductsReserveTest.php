<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReleaseProductsReserveTest extends TestCase
{
    use RefreshDatabase;
    private const ROUTE = '/api/products/releaseReserve';

    /**
     * освобождение резерва товаров на доступном складе
     */
    public function test_releasing_a_reserve_of_goods_in_an_available_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', true)->first();
        $products = [1, 2, 3];

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stock->getId(),
            'products' => $products,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'updated' => 3,
        ]);

        Product::query()
            ->whereRelation('stock', 'id', '=', $stock->getId())
            ->whereIn('code', $products)
            ->each(function (Product $product) {
                $this->assertEquals(4, $product->getAttribute('quantity'));
                $this->assertEquals(0, $product->getAttribute('quantity_in_reserve'));
            });
    }

    /**
     * освобождение резерва товаров на не существующем складе
     */
    public function test_releasing_a_reserve_of_goods_in_a_non_existent_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->orderByDesc('id')->first();

        $stockId = $stock->getId() + 1;

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stockId,
            'products' => [1],
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => sprintf('Stock %d not found.', $stockId),
        ]);
    }

    /**
     * освобождение резерва товаров на недоступном складе
     */
    public function test_releasing_a_reserve_of_goods_in_an_inaccessible_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', false)->first();

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stock->getId(),
            'products' => [1],
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'message' => sprintf('Stock %d not available.', $stock->getId()),
        ]);
    }

    /**
     * освобождение резерва не существующего товара
     */
    public function test_release_of_reserve_of_non_existent_goods(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', true)->first();

        /** @var Product $product */
        $product = Product::query()->orderByDesc('code')->first();
        $code = $product->getAttribute('code') + 1;

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stock->getId(),
            'products' => [1, 2, 3, $code],
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => sprintf('Product %d not found.', $code),
        ]);
    }

    /**
     * освобождение резерва товаров на всех доступных складах
     */
    public function test_releasing_the_stock_of_goods_in_all_available_warehouses(): void
    {
        $this->seed(TestSeeder::class);

        $products = [1, 2, 3];

        $response = $this->post(self::ROUTE, [
            'products' => $products,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'updated' => 3,
        ]);

        Product::query()
            ->whereRelation('stock', 'available', '=', true)
            ->whereIn('code', $products)
            ->each(function (Product $product) {
                $this->assertEquals(4, $product->getAttribute('quantity'));
                $this->assertEquals(0, $product->getAttribute('quantity_in_reserve'));
            });
    }
}
