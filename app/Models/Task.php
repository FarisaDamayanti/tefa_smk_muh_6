<?php

namespace App\Models;

use App\Enums\SubmissionTypeEnum;
use App\Traits\Models\BelongsToClassroom;
use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\BelongsToTopic;
use App\Traits\Models\HasManyTaskSubmission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, HasUuids, SoftDeletes, BelongsToTopic, HasManyTaskSubmission;

    protected $guarded = ['id'];

    protected $casts = [
        'deadline' => 'datetime',
        'submission_type' => SubmissionTypeEnum::class
    ];

    public function currentUserTaskSubmission(): HasOne
    {
        return $this->hasOne(TaskSubmission::class, 'task_id')->where('student_id', auth_student()->id);
    }

    public function latestTaskSubmission(): HasMany
    {
        return $this->hasMany(TaskSubmission::class, 'task_id')
        ->whereIn('id', function ($q) {
            $q->selectRaw('MAX(id)')
              ->from('task_submissions as ts2')
              ->whereColumn('ts2.student_id', 'task_submissions.student_id')
              ->whereColumn('ts2.task_id', 'task_submissions.task_id');
        });
    }
}
