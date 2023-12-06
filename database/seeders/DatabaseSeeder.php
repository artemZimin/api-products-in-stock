<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private const SCALE = 3;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Stock::factory(self::SCALE)->create();

        Stock::all()->each(function (Stock $stock) {
            for ($i = 1; $i <= self::SCALE; $i++) {
                Product::factory(['code' => $i, 'stock_id' => $stock->getId()])->create();
            }
        });
    }
}
