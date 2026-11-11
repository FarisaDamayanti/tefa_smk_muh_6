<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\LevelClassInterface;
use App\Models\LevelClass;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LevelClassRepository extends BaseRepository implements LevelClassInterface
{
    public function __construct(LevelClass $levelClass)
    {
        $this->model = $levelClass;
    }

    public function get(): mixed
    {
        return $this->model->query()->get();
    }

    public function getSort(): Collection
    {
        return $this->model->query()->orderBy('name', 'asc')->get();
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

    public function where(mixed $data): mixed
    {
        return $this->model->query()->where('school_id', $data)->get();
    }

    public function search(Request $request): mixed
    {
        return $this->model->query()
            ->when($request->name, function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' .  $request->name . '%');
            })
            ->latest()->paginate(10);
    }

    public function duplicate(mixed $query): mixed
    {
        return $this->model->query()->where('name', 'LIKE', '%' . $query . '%')->first();
    }

    /**
     * get all data or get by id
     * 
     * @param Request $request
     * @return Collection
     */
    public function getAndFilterById(Request $request): Collection
    {
        return $this->model->query()
            ->when($request->level_class_id, function($query) use ($request) {
                $query->where('id', $request->level_class_id);
            })
            ->get();
    }
}
