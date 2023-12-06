<?php

namespace App\Contracts\Actions;

use App\Contracts\Repository\ProductRepositoryContract;
use App\DTO\ProductDTO;
use App\DTO\ProductForReservationDTO;
use App\Exceptions\DomainException;
use App\Exceptions\NotFoundException;

interface ProductReservationContract
{
    public function __construct(ProductRepositoryContract $productRepository);


    /**
     * Резервирование товара
     *
     * Резервирует товар, или выбрасывает ошибку
     * если нет доступного количества товаров
     * или товар не найден
     *
     * @param ProductDTO $productDTO
     * @param ProductForReservationDTO $productForReservationDTO
     * @return void
     * @throws DomainException
     * @throws NotFoundException
     */
    public function handle(
        ProductDTO $productDTO,
        ProductForReservationDTO $productForReservationDTO
    ): void;
}
