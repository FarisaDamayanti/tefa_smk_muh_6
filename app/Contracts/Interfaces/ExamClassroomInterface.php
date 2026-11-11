<?php
        
namespace App\Contracts\Interfaces;
        
use App\Contracts\Interfaces\Eloquent\DeleteInterface; 
use App\Contracts\Interfaces\Eloquent\GetInterface; 
use App\Contracts\Interfaces\Eloquent\ShowInterface; 
use App\Contracts\Interfaces\Eloquent\StoreInterface; 
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
        
interface ExamClassroomInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface 
{
    /**
     * delete by exam_id
     *
     * @param string $exam_id
     * @return integer
     */
    public function deleteByExam(string $exam_id): int;

    /**
     * query delete where in
     *
     * @param string $exam_id
     * @param array $classroom_ids
     * @return boolean
     */
    public function deleteWhereIn(string $exam_id, array $classroom_ids): bool;
}