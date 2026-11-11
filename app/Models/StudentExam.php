<?php

namespace App\Models;

use App\Traits\Models\BelongsToExam;
use App\Traits\Models\BelongsToStudent;
use App\Traits\Models\HasManyStudentQuestionAnswer;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentExam extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToStudent,
        BelongsToExam,
        HasManyStudentQuestionAnswer;

    protected $guarded = ['id'];

    protected $fillable = [
        'student_id',
        'exam_id',
        'score',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
