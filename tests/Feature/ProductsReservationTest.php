<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use Database\Seeders\TestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductsReservationTest extends TestCase
{
    use RefreshDatabase;
    private const ROUTE = '/api/products/reservation';

    /**
     * резервирование товара на доступном складе
     */
    public function test_reserving_goods_in_an_available_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', true)->first();

        $products = [
            [
                'code' => 1,
                'count' => 1,
            ],
            [
                'code' => 2,
                'count' => 1,
            ],
            [
                'code' => 3,
                'count' => 1,
            ],
        ];

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stock->getId(),
            'products' => $products,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'updated' => count($products),
        ]);

        Product::query()
            ->whereRelation('stock', 'id', '=', $stock->getId())
            ->whereIn('code', array_map(fn ($product) => $product['code'], $products))
            ->each(function (Product $product) {
                $this->assertEquals(1, $product->getAttribute('quantity'));
                $this->assertEquals(3, $product->getAttribute('quantity_in_reserve'));
            });
    }

    /**
     * резервирование товара на недоступном складе
     */
    public function test_reserving_goods_at_an_inaccessible_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->where('available', '=', false)->first();

        $products = [
            [
                'code' => 1,
                'count' => 1,
            ],
        ];

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stock->getId(),
            'products' => $products,
        ]);

        $response->assertStatus(400);

        $response->assertJson([
            'message' => sprintf('Stock %d not available.', $stock->getId()),
        ]);
    }

    /**
     * резервирование товара на несуществующем складе
     */
    public function test_reserving_goods_at_a_non_existent_warehouse(): void
    {
        $this->seed(TestSeeder::class);

        /** @var Stock $stock */
        $stock = Stock::query()->orderByDesc('id')->first();

        $products = [
            [
                'code' => 1,
                'count' => 1,
            ],
        ];

        $stockId = $stock->getId() + 1;

        $response = $this->post(self::ROUTE, [
            'stock_id' => $stockId,
            'products' => $products,
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => sprintf('Stock %d not found.', $stockId),
        ]);
    }

    /**
     * резервирование товара на всех доступных складах
     */
    public function test_reserving_goods_in_all_available_warehouses(): void
    {
        $this->seed(TestSeeder::class);

        $products = [
            [
                'code' => 1,
                'count' => 1,
            ],
            [
                'code' => 2,
                'count' => 1,
            ],
            [
                'code' => 3,
                'count' => 1,
            ],
        ];

        $response = $this->post(self::ROUTE, [
            'products' => $products,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'updated' => count($products),
        ]);

        foreach ($products as $product) {
            $queryProduct = Product::query()
                ->select(DB::raw('sum(quantity)'))
                ->whereRelation('stock', 'available', '=', true)
                ->groupBy('code')
                ->having('code', '=', $product['code'])
                ->first();

            $sum = (int)$queryProduct->getAttribute('sum');
            $this->assertEquals(3, $sum);
        }
    }

    /**
     * резервирование несуществующего товара
     */
    public function test_reservation_of_a_non_existent_product(): void
    {
        $this->seed(TestSeeder::class);

        $product = Product::query()->orderByDesc('code')->first();
        $code = $product->getAttribute('code') + 1;

        $products = [
            [
                'code' => $code,
                'count' => 1,
            ],
        ];

        $response = $this->post(self::ROUTE, [
            'products' => $products,
        ]);

        $response->assertStatus(404);

        $response->assertJson([
            'message' => sprintf('Product %d not found.', $code),
        ]);
    }
}
