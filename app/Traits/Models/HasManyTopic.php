<?php

namespace App\Traits\Models;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyTopic {

    /**
     * Get all owned topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }

}
