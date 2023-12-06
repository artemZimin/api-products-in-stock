<?php

namespace App\Actions;

use App\Contracts\Actions\AtomicLocksContract;
use Illuminate\Cache\Lock;

class AtomicLocks implements AtomicLocksContract
{
    public function handle(callable $handle): mixed
    {
        try {
            /** @var Lock[] $locks */
            $locks = [];

            $result = $handle($locks);
        } finally {
            foreach ($locks as $lock) {
                $lock->release();
            }
        }

        return $result;
    }
}
