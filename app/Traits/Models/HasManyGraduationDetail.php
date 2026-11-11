<?php

namespace App\Traits\Models;

use App\Models\GraduationDetail;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyGraduationDetail {

    /**
     * Get all of the graduationDetails for the HasManyGraduationDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function graduationDetails(): HasMany
    {
        return $this->hasMany(GraduationDetail::class);
    }

}
