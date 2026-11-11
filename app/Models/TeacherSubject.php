<?php

namespace App\Models;

use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\HasManyLessonSchedule;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSubject extends Model
{
    use HasFactory, HasUuids, BelongsToEmployee, BelongsToSubject;

    protected $guarded = ['id'];

    /**
     * Get all of the students for the HasManyStudent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonScheadules(): HasMany
    {
        return $this->hasMany(LessonSchedule::class);
    }
}
