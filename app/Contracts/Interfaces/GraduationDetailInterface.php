<?php
        
namespace App\Contracts\Interfaces;
        
use App\Contracts\Interfaces\Eloquent\DeleteInterface; 
use App\Contracts\Interfaces\Eloquent\GetInterface; 
use App\Contracts\Interfaces\Eloquent\ShowInterface; 
use App\Contracts\Interfaces\Eloquent\StoreInterface; 
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Models\GraduationDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface GraduationDetailInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface 
{
    /**
     * bulk upsert
     * 
     * @param array $data
     * @param array $uniqueBy
     * @param array $update
     * @return void
     */
    public function upsert(array $data, array $uniqueBy, array $update = null): int;

    /**
     * get data by student
     * 
     * @param mixed $student_id
     * @return GraduationDetail|null
     */
    public function getByStudent(mixed $student_id): GraduationDetail|null;

    /**
     * update status open by student
     * 
     * @param mixed $student_id
     * @return bool
     */
    public function updateStatusOpenByStudent(mixed $student_id): bool;

    /**
     * get by graduation id and key by student id
     * 
     * @param string $graduation_id
     * @return Collection
     */
    public function getByGraduation(string $graduation_id): Collection;

    /**
     * query insert data
     * 
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool;

    /**
     * query bulk update
     * 
     * @param array $updates
     * @param Carbon $now
     * @return void
     */
    public function bulkUpdateStatus(array $updates, Carbon $now): void;
}