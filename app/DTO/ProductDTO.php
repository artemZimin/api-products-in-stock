<?php

namespace App\DTO;

use App\Enums\ProductSizeTypeEnum;

class ProductDTO
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $code,
        private readonly int $quantity,
        private readonly int $quantityInReserve,
        private readonly float $size,
        private readonly ProductSizeTypeEnum $sizeType,
        private readonly int $stockId
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getQuantityInReserve(): int
    {
        return $this->quantityInReserve;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function getSizeType(): ProductSizeTypeEnum
    {
        return $this->sizeType;
    }

    public function getStockId(): int
    {
        return $this->stockId;
    }
}
