<?php

namespace App\Traits\Models;

use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTaskSubmission
{
    /**
     * Get the user that owns the BelongsToTaskSubmission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskSubmission(): BelongsTo
    {
        return $this->belongsTo(TaskSubmission::class);
    }
}
