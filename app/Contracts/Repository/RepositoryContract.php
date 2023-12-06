<?php

namespace App\Contracts\Repository;

interface RepositoryContract
{
    public const LIMIT_PER_QUERY = 500;

    /**
     * Оборачивает код в транзакцию
     *
     * @param callable $handle
     * @return void
     */
    public function transaction(callable $handle): void;
}
