<?php

namespace App\Traits\Models;

use App\Models\TaskQuestion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTaskQuestion
{
    /**
     * Get the user that owns the BelongsToTaskQuestion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taskQuestion(): BelongsTo
    {
        return $this->belongsTo(TaskQuestion::class);
    }
}
