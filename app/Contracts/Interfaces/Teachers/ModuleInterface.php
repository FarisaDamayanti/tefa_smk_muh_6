<?php

namespace App\Contracts\Interfaces\Teachers;

use App\Models\Module;
use Illuminate\Database\Eloquent\Collection;

interface ModuleInterface
{
    public function showWithRelation(string $id): Module;
    public function whereStudent(string $id): Collection;
}
