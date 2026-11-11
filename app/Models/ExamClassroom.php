<?php

namespace App\Models;

use App\Traits\Models\BelongsToClassroom;
use App\Traits\Models\BelongsToExam;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamClassroom extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToClassroom,
        BelongsToExam;

    protected $guarded = ['id'];

    protected $fillable = [
        'classroom_id',
        'exam_id'
    ];
}
