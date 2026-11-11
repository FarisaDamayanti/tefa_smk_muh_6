<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\SearchInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ExamInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, SearchInterface
{
    public function bulkUpdate(array $ids, array $data): mixed;
    public function updateWhere(array $attributes, array $data): int;
    /**
     * Search exam with teacher
     *
     * @param Request $request
     * @return Collection
     */
    public function searchDetail(Request $request): Collection;
    /**
     * Get student's incoming exam within 4 days before exam
     *
     * @param string $student
     * @return Collection
     */
    public function getInComingExamByStudent(string $student): Collection;
}
