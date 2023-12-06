<?php

namespace App\Models;

use App\DTO\ProductDTO;
use App\Enums\ProductSizeTypeEnum;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function setQuantity(int $quantity): void
    {
        $this->setAttribute('quantity', $quantity);
    }

    public function setQuantityInReserve(int $quantityInReserve): void
    {
        $this->setAttribute('quantity_in_reserve', $quantityInReserve);
    }

    /**
     * @return ProductDTO
     * @throws Exception
     */
    public function toDTO(): ProductDTO
    {
        $id = $this->getAttributeFromArray('id');
        $name = $this->getAttributeFromArray('name');
        $code = $this->getAttributeFromArray('code');
        $quantity = $this->getAttributeFromArray('quantity');
        $quantityInReserve = $this->getAttributeFromArray('quantity_in_reserve');
        $size = $this->getAttributeFromArray('size');
        $sizeType = $this->getAttributeFromArray('size_type');
        $stockId = $this->getAttributeFromArray('stock_id');

        $sizeType = ProductSizeTypeEnum::tryFrom($sizeType);

        if ($id === null) {
            throw new Exception('ID is empty.');
        }

        if ($name === null) {
            throw new Exception('Name is empty.');
        }

        if ($code === null) {
            throw new Exception('Code is empty.');
        }

        if ($quantity === null) {
            throw new Exception('Quantity is empty.');
        }

        if ($quantityInReserve === null) {
            throw new Exception('Quantity in reserve is empty.');
        }

        if ($size === null) {
            throw new Exception('Size is empty.');
        }

        if ($sizeType === null) {
            throw new Exception('Size type is empty.');
        }

        if ($stockId === null) {
            throw new Exception('Stock ID is empty.');
        }

        return new ProductDTO(
            (int)$id,
            (string)$name,
            (int)$code,
            (int)$quantity,
            (int)$quantityInReserve,
            (float)$size,
            $sizeType,
            (int)$stockId
        );
    }
}
