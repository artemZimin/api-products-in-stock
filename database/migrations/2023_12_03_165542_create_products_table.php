<?php

use App\Enums\ProductSizeTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sizeTypes = ProductSizeTypeEnum::cases();
        $sizeTypes = array_column($sizeTypes, 'value');

        Schema::create('products', function (Blueprint $table) use ($sizeTypes) {
            $table->id();
            $table->string('name', 40);
            $table->unsignedInteger('code');
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_in_reserve');
            $table->unsignedFloat('size');
            $table->enum('size_type', $sizeTypes);
            $table->foreignId('stock_id')->references('id')->on('stocks');
            $table->unique(['code', 'stock_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
