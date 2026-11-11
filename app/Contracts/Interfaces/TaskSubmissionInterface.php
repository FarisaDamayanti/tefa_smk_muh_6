<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\InsertInterface;

interface TaskSubmissionInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface
{
    /**
     * check name file and return boolean
     *
     * @param string $filename
     * @return bool
     */
    public function checkName(string $filename): bool;

    /**
     * Perform bulk update on given ids
     *
     * @param array $ids
     * @param array $data
     * @return mixed
     */
    public function bulkUpdate(array $ids, array $data): mixed;
}
