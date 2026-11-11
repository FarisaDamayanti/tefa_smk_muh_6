<?php

namespace App\Models;

use App\Traits\Models\BelongsToSchool;
use App\Traits\Models\HasManyClassroom;
use App\Traits\Models\HasManyExam;
use App\Traits\Models\HasManyExamQuestion;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LevelClass extends Model
{
    use HasFactory, HasUuids, BelongsToSchool, HasManyClassroom, HasManyExam, HasManyExamQuestion;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'school_id'
    ];

}
