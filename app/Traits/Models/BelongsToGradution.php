<?php

namespace App\Traits\Models;

use App\Models\Graduation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToGradution {

    /**
     * Get the school that owns the BelongsToGradution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function graduation(): BelongsTo
    {
        return $this->belongsTo(Graduation::class);
    }

}
