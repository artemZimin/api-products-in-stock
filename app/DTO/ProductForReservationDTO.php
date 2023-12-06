<?php

namespace App\DTO;

use Exception;

class ProductForReservationDTO
{
    public function __construct(
        private readonly int $code,
        private readonly int $count,
    ) {
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): ProductForReservationDTO
    {
        $code = $data['code'];
        $count = $data['count'];

        if ($code === null) {
            throw new Exception('Code is empty.');
        }

        if ($count === null) {
            throw new Exception('Count is empty.');
        }

        return new self((int)$data['code'], (int)$data['count']);
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
