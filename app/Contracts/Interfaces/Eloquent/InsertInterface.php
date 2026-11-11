<?php

namespace App\Contracts\Interfaces\Eloquent;

interface InsertInterface
{
    /**
     * Handle insert many data to database.
     * ___
     * Doesn't have model event.
     *
     * @param array[] $name
     * @return int
     */

    public function insert(array $data): int;
}
