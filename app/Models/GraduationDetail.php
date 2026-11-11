<?php

namespace App\Models;

use App\Traits\Models\BelongsToGradution;
use App\Traits\Models\BelongsToStudent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GraduationDetail extends Model
{
    use HasUuids,
        SoftDeletes,
        BelongsToStudent,
        BelongsToGradution;

    protected $guarded = ['id'];
    protected $fillable = [
        'student_id',
        'graduation_id',
        'status',
        'is_open',
    ];
}
