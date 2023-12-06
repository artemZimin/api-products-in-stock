<?php

namespace App\Http\Requests;

class ProductsCountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [];
    }

    public function getStockId(): ?int
    {
        $stock = $this->query('stock_id');

        return $stock !== null ? (int)$stock : null;
    }
}
