<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\GraduationInterface;
use App\Models\Graduation;

class GraduationRepository extends BaseRepository implements GraduationInterface
{
    public function __construct(Graduation $Graduation)
    {
        $this->model = $Graduation;
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

    /**
     * check exists data by school year id
     * 
     * @param int $school_year_id
     * @return Graduation|null
     */
    public function getBySchoolYear(mixed $school_year_id): Graduation|null
    {
        return $this->model->query()
            ->with(['graduationDetails'])
            ->where('school_year_id', $school_year_id)
            ->first();
    }

    /**
     * check file name
     * 
     * @param string $fileName
     * @return bool
     */
    public function checkFileName(string $fileName): bool
    {
        return $this->model->query()
            ->where('additional_file', $fileName)
            ->exists();
    }
}