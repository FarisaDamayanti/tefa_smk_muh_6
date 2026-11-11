<?php
        
namespace App\Contracts\Interfaces;
        
use App\Contracts\Interfaces\Eloquent\DeleteInterface; 
use App\Contracts\Interfaces\Eloquent\GetInterface; 
use App\Contracts\Interfaces\Eloquent\ShowInterface; 
use App\Contracts\Interfaces\Eloquent\StoreInterface; 
use App\Contracts\Interfaces\Eloquent\UpdateInterface;
use App\Models\StudentQuestionAnswer;
use Illuminate\Support\Collection;

interface StudentQuestionAnswerInterface extends GetInterface, StoreInterface, UpdateInterface, ShowInterface, DeleteInterface 
{
    /**
     * query bulk insert for user question answers
     *
     * @param array $data
     * @return boolean
     */
    public function bulkInsert(array $data): bool;

    /**
     * query delete data by student_exam_id
     *
     * @param string $student_exam_id
     * @return boolean
     */
    public function deleteByStudentExam(string $student_exam_id): bool;

    /**
     * delete where in by student exam id
     *
     * @param array $student_exam_ids
     * @return bool
     */
    public function deleteWhereInByStudentExamId(array $student_exam_ids): bool;

    /**
     * get total all score exam question type matching and essay
     *
     * @param string $student_exam_id
     * @param string $student_question_answer_id
     * @return StudentQuestionAnswer
     */
    public function getTotalScoreExamQuestion(string $student_exam_id, string $student_question_answer_id): StudentQuestionAnswer;

    /**
     * get exam question essay not yet rated count
     *
     * @param string $student_exam_id
     * @return integer
     */
    public function getQuestionEssayNotYetRatedCount(string $student_exam_id): int;

    /**
     * query get essay question by student_exam_id
     *
     * @param string $student_exam_id
     * @return Collection
     */
    public function getEssayByStudent(string $student_exam_id): Collection;
}