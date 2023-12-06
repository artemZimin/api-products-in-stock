<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($s = 0; $s < 3; $s++) {
            $stock = Stock::factory()->create([
                'available' => (bool)$s,
            ]);

            for ($i = 0; $i < 3; $i++) {
                Product::factory()->create([
                    'code' => $i + 1,
                    'stock_id' => $stock->getId(),
                    'quantity' => 2,
                    'quantity_in_reserve' => 2,
                ]);
            }
        }
    }
}
