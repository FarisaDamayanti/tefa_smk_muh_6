<?php

namespace App\Traits\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTask {

    /**
     * Get all of the task for the HasManyTask
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
