<?php

namespace App\Traits\Models;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTopic {

    /**
     * Get the task that owns the BelongsToTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

}
