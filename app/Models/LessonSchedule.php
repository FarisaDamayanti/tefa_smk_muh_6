<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\BelongsToClassroom;
use App\Traits\Models\BelongsToLessonHour;

use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\HasManyTeacherJournal;
use App\Traits\Models\BelongsToTeacherSubject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonSchedule extends Model
{
    use HasFactory, HasUuids, BelongsToLessonHour, BelongsToSchoolYear, BelongsToTeacherSubject, BelongsToClassroom, HasManyTeacherJournal;

    protected $guarded = ['id'];
    protected $fillable = ['classroom_id', 'lesson_hour_start', 'lesson_hour_end', 'teacher_subject_id', 'school_year_id', 'day'];

    /**
     * Get all of the journals for the LessonSchedule
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function journals(): HasMany
    {
        return $this->hasMany(TeacherJournal::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * Only for relational chaining with specified data
     */
    public function teacherJournal(){
        return $this->hasOne(TeacherJournal::class);
    }
}
