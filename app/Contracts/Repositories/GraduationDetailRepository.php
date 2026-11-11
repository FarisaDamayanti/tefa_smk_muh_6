<?php

namespace App\Contracts\Repositories;

use App\Contracts\Interfaces\GraduationDetailInterface;
use App\Models\GraduationDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GraduationDetailRepository extends BaseRepository implements GraduationDetailInterface
{
    public function __construct(GraduationDetail $GraduationDetail)
    {
        $this->model = $GraduationDetail;
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
     * bulk upsert
     * 
     * @param array $data
     * @return bool
     */
    public function upsert(array $data, array $uniqueBy, array $update = null): int
    {
        return $this->model->query()->upsert($data, $uniqueBy, $update);
    }

    /**
     * get data by student
     * 
     * @param mixed $student_id
     * @return GraduationDetail|null
     */
    public function getByStudent(mixed $student_id): GraduationDetail|null
    {
        return $this->model->query()
            ->with(['graduation'])
            ->where('student_id', $student_id)
            ->latest()
            ->first();
    }

    /**
     * update status open by student
     * 
     * @param mixed $student_id
     * @return bool
     */
    public function updateStatusOpenByStudent(mixed $student_id): bool
    {
        return $this->model->query()
            ->where('student_id', $student_id)
            ->update(['is_open' => 1]);
    }

    /**
     * get by graduation id and key by student id
     * 
     * @param string $graduation_id
     * @return Collection
     */
    public function getByGraduation(string $graduation_id): Collection
    {
        return $this->model->query()
            ->where('graduation_id', $graduation_id)
            ->get()
            ->keyBy('student_id');
    }

    /**
     * query insert data
     * 
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        return $this->model->query()->insert($data);
    }

    /**
     * query bulk update
     * 
     * @param array $updates
     * @param Carbon $now
     * @return void
     */
    public function bulkUpdateStatus(array $updates, Carbon $now): void
    {
        $cases = [];
        $ids = [];

        foreach ($updates as $id => $status) {
            $safeId = addslashes($id);
            $safeStatus = addslashes($status);

            $cases[] = "WHEN '{$safeId}' THEN '{$safeStatus}'";
            $ids[] = "'{$safeId}'";
        }

        $caseSql = implode(' ', $cases);
        $idsSql = implode(',', $ids);

        DB::statement("
            UPDATE graduation_details
            SET
                status = CASE id
                    {$caseSql}
                END,
                updated_at = '{$now}'
            WHERE id IN ({$idsSql})
        ");
    }
}