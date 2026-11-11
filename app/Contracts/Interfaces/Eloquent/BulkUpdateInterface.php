<?php

namespace App\Contracts\Interfaces\Eloquent;

interface BulkUpdateInterface
{
    /**
     * Handle bulk update on given ids.
     *
     * @param array $ids
     *
     * @return mixed
     */
    public function bulkUpdate(array $ids, array $data): mixed;
}
