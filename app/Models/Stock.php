<?php

namespace App\Models;

use App\DTO\StockDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    use HasFactory;

    public function getId(): int
    {
        return (int)$this->getAttributeFromArray('id');
    }

    public function toDTO(): StockDTO
    {
        return new StockDTO(
            (int)$this->getAttributeFromArray('id'),
            (string)$this->getAttributeFromArray('name'),
            (bool)$this->getAttributeFromArray('available'),
        );
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
