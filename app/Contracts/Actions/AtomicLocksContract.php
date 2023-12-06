<?php

namespace App\Contracts\Actions;

interface AtomicLocksContract
{
    /**
     * Обработка массива атомарных блокировок
     *
     * Обрабаывает массив атомарных блокировок,
     * завершает их всех когда callback заершает работу,
     * либо в нем падает ошибка
     *
     * @param callable $handle
     * @return mixed
     */
    public function handle(callable $handle): mixed;
}
