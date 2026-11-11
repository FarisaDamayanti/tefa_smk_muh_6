<?php

namespace App\Models;

use App\Traits\Models\BelongsToExamQuestionMatching;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestionMatchingAnswer extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToExamQuestionMatching;

    protected $guarded = ['id'];

    protected $fillable = [
        'left_item_id',
        'right_item_id',
    ];
}
