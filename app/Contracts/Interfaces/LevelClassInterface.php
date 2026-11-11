<?php

namespace App\Contracts\Interfaces;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\DuplicateInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Eloquent\WhereInterface;
use App\Contracts\Interfaces\Eloquent\WhereSchoolInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface LevelClassInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface, WhereInterface
{
    public function getSort(): Collection;
    public function search(Request $request): mixed;
    public function duplicate(mixed $query): mixed;

    /**
     * get all data or get by id
     * 
     * @param Request $request
     * @return Collection
     */
    public function getAndFilterById(Request $request): Collection;
}
