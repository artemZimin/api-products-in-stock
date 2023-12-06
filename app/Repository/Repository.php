<?php

namespace App\Repository;

use App\Contracts\Repository\RepositoryContract;
use Illuminate\Support\Facades\DB;

class Repository implements RepositoryContract
{
    public function transaction(callable $handle): void
    {
        DB::transaction($handle);
    }
}
