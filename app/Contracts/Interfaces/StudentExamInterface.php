<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\SearchInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Models\StudentExam;
use Illuminate\Support\Collection;

interface StudentExamInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, SearchInterface
{
    public function hasUsedOnExam(string $id): bool;

    /**
     * query get first or if there isn't then create
     *
     * @param array $attributes
     * @param array $values
     * @return StudentExam
     */
    public function firstOrCreate(array $attributes, array $values): StudentExam;

    /**
     * delete where in by id
     *
     * @param array $ids
     * @return bool
     */
    public function deleteWhereInById(array $ids): bool;

    /**
     * get where in by id
     *
     * @param array $ids
     * @return Collection
     */
    public function whereInById(array $ids): Collection;

    /**
     * get single choice dan multiple choice count
     *
     * @param string $id
     * @return integer
     */
    public function getSingleAndMultipleChoiceCount(string $id): int;
}
