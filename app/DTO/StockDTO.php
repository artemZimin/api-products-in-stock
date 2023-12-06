<?php

namespace App\DTO;

class StockDTO
{
    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly bool $available
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

    public function getAvailable(): bool
    {
        return $this->available;
    }
}
