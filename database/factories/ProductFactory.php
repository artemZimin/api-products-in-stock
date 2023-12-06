<?php

namespace Database\Factories;

use App\Enums\ProductSizeTypeEnum;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(0, 10);
        $quantityInReserve = fake()->numberBetween(0, $quantity);

        $sizeTypes = ProductSizeTypeEnum::cases();
        $sizeTypes = array_column($sizeTypes, 'value');

        return [
            'name' => fake()->text(40),
            'quantity' => $quantity,
            'quantity_in_reserve' => $quantityInReserve,
            'size' => fake()->randomFloat(2, 1, 1000),
            'size_type' => $sizeTypes[array_rand($sizeTypes)],
        ];
    }
}
