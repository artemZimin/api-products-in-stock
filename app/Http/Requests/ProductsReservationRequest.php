<?php

namespace App\Http\Requests;

use App\DTO\ProductForReservationDTO;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductsReservationRequest extends Request
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
            'products.*.code' => ['required', 'integer', 'distinct'],
            'products.*.count' => ['required', 'integer'],
        ];
    }

    /**
     * @return ProductForReservationDTO[]
     * @throws Exception
     */
    public function getProducts(): array
    {
        return array_map(function (array $product) {
            return ProductForReservationDTO::fromArray($product);
        }, (array)$this->input('products'));
    }
}
