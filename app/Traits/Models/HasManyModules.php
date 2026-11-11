<?php

namespace App\Traits\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasManyModules {

    /**
     * Get all of modules
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class);
    }

}
