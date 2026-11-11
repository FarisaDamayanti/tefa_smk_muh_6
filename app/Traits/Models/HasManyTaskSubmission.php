<?php

namespace App\Traits\Models;

use App\Models\TaskSubmission;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTaskSubmission {

    /**
     * Get all of the taskSubmissions for the HasManyTaskSubmission
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskSubmissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
