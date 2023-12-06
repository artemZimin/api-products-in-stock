<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class ReleaseProductsReserveRequest extends Request
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'stock_id' => ['integer'],
            'products' => ['required', 'array'],
            'products.*' => ['integer', 'distinct'],
        ];
    }

    /**
     * @return int[]
     */
    public function getProducts(): array
    {
        return array_map(fn ($product) => (int)$product, $this->input('products'));
    }
}
