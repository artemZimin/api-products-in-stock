<?php

namespace App\DTO;

class ProductUpdateQuantityDTO
{
    public function __construct(
        private readonly int $id,
        private readonly int $quantity,
        private readonly int $quantityInReserve,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getQuantityInReserve(): int
    {
        return $this->quantityInReserve;
    }
}
