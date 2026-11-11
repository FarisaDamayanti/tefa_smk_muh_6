<?php

namespace App\Models;

use App\Traits\Models\BelongsToStudent;
use App\Traits\Models\BelongsToTask;
use App\Traits\Models\BelongsToTopic;
use App\Traits\Models\BelongsToUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory,
        BelongsToTask,
        BelongsToStudent,
        BelongsToTopic,
        HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'submit_date' => 'datetime',
    ];
}
