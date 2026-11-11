<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\Eloquent\DeleteInterface;
use App\Contracts\Interfaces\Eloquent\GetInterface;
use App\Contracts\Interfaces\Eloquent\PaginateInterface;
use App\Contracts\Interfaces\Eloquent\ShowInterface;
use App\Contracts\Interfaces\Eloquent\StoreInterface;
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Contracts\Interfaces\Teachers\ModuleInterface;
use App\Contracts\Repositories\BaseRepository;
use App\Models\Module;
use Illuminate\Database\Eloquent\Collection;

class ModuleRepository extends BaseRepository implements
    GetInterface,
    StoreInterface,
    UpdateInterface,
    ShowInterface,
    DeleteInterface,
    PaginateInterface,
    ModuleInterface
{
    public function __construct(Module $module)
    {
        $this->model = $module;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function store(array $data): mixed
    {
        return $this->model->query()->create($data);
    }

    public function show(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id);
    }

    public function update(mixed $id, array $data): mixed
    {
        return $this->model->query()->findOrFail($id)->update($data);
    }

    public function delete(mixed $id): mixed
    {
        return $this->model->query()->findOrFail($id)->delete();
    }

    public function paginate(): mixed
    {
        return $this->model->query()
            ->with(['classroom', 'employee', 'subject'])
            ->whereRelation('employee.user', 'id', auth()->id())->paginate(10);
    }

    public function showWithRelation(string $id): Module
    {
        return $this->model->query()->with(['classroom', 'employee', 'subject'])->findOrFail($id);
    }

    public function whereStudent(string $id): Collection
    {
        return $this->model->query()
            ->whereRelation('classroom.classroomStudents.student', 'id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
