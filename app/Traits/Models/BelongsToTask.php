<?php

namespace App\Traits\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTask {

    /**
     * Get the task that owns the BelongsToTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

}
