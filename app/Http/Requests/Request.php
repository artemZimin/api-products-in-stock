<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function getStockId(): ?int
    {
        $stock = $this->input('stock_id');

        return $stock !== null ? (int)$stock : null;
    }
}
