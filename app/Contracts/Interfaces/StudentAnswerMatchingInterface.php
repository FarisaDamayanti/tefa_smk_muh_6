<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;

interface StudentAnswerMatchingInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * query bulk insert
     *
     * @param array $data
     * @return boolean
     */
    public function bulkInsert(array $data): bool;
}
    