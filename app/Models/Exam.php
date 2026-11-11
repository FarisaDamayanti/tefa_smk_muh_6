<?php

namespace App\Models;

use App\Traits\Models\BelongsToQuestionBank;
use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\HasManyExamClassroom;
use App\Traits\Models\HasManyStudentExam;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Exam extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToQuestionBank,
        BelongsToSchoolYear,
        HasManyExamClassroom,
        HasManyStudentExam;

    protected $guarded = ['id'];

    protected $fillable = [
        'question_bank_id',
        'school_year_id',
        'semester',
        'title',
        'exam_date',
        'duration',
        'minimum_score',
        'rules',
        'total_questions',
        'allow_early_submission',
        'token',
        'can_view_answer_result',
    ];

    protected $casts = [
        'exam_date' => 'datetime',
    ];

    /**
     * get end time of exam
     *
     * @return Carbon
     */
    public function getExamEndTime(): Carbon
    {
        return $this->exam_date->copy()->addMinutes($this->duration);
    }
}
