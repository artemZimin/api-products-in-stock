<?php

namespace Tests\Feature;

use App\Models\Stock;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsCountTest extends TestCase
{
    use RefreshDatabase;
    private const ROUTE = '/api/products/count';

    /**
     * получение кол-ва оставшихся товаров на всех доступных складах
     */
    public function test_obtaining_the_number_of_remaining_goods_in_all_available_warehouses(): void
    {
        $this->seed(TestSeeder::class);

        $response = $this->get(self::ROUTE);

        $response->assertStatus(200);

        $response->assertJson([
            'result' => 12,
        ]);
    }

    /**
     * получение кол-ва оставшихся товаров на одном доступном складе
     */
    public function test_obtaining_the_number_of_remaining_goods_in_one_available_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', true)->first();

        $response = $this->get(sprintf(self::ROUTE . '?stock_id=%d', $stock->getId()));

        $response->assertStatus(200);

        $response->assertJson([
            'result' => 6,
        ]);
    }

    /**
     * получение кол-ва оставшихся товаров на не доступном складе
     */
    public function test_receiving_the_number_of_remaining_goods_in_an_unavailable_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', false)->first();

        $response = $this->get(sprintf(self::ROUTE . '?stock_id=%d', $stock->getId()));

        $response->assertStatus(400);

        $response->assertJson([
            'message' => sprintf('Stock %d not available.', $stock->getId()),
        ]);
    }

    /**
     * получение кол-ва оставшихся товаров на не существующем складе
     */
    public function test_obtaining_the_number_of_remaining_goods_in_a_non_existent_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->orderByDesc('id')->first();

        $stockId = $stock->getId() + 1;

        $response = $this->get(sprintf(self::ROUTE . '?stock_id=%d', $stockId));

        $response->assertStatus(404);

        $response->assertJson([
            'message' => sprintf('Stock %d not found.', $stockId),
        ]);
    }
}
