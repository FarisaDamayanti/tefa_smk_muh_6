<?php

namespace App\Models;

use App\Traits\Models\BelongsToLevelClass;
use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\HasManyExam;
use App\Traits\Models\HasManyExamQuestion;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToSubject,
        BelongsToLevelClass,
        HasManyExamQuestion,
        HasManyExam;

    protected $guarded = ['id'];

    protected $fillable = [
        'title',
        'subject_id',
        'level_class_id',
        'description',
        'question_limit',
    ];

    public function getActiveQuestionCount()
    {
        return $this->examQuestions()->where('is_active', 1)->count();
    }
}
