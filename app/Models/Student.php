<?php

namespace App\Models;

use App\Enums\GenderEnum;
use App\Traits\Models\BelongsToReligion;
use App\Traits\Models\BelongsToSchool;
use App\Traits\Models\BelongsToUser;
use App\Traits\Models\HasManyClassroomStudent;
use App\Traits\Models\HasManyExtracurricularStudent;
use App\Traits\Models\HasManyStudentExam;
use App\Traits\Models\HasManyTaskSubmission;
use App\Traits\Models\HasManyGraduationDetail;
use App\Traits\Models\MorphManyRfid;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, HasUuids, BelongsToUser,
    BelongsToReligion, HasManyClassroomStudent,
    HasManyExtracurricularStudent, MorphManyRfid, HasManyTaskSubmission,
    HasManyStudentExam, HasManyGraduationDetail;

    protected $guarded = ['id'];
    protected $casts = [
        'gender' => GenderEnum::class
    ];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    /**
     * get student classroom relation by school year active
     * 
     * @return HasOne
     */
    public function currentStudentClassroom(): HasOne
    {
        return $this->hasOne(ClassroomStudent::class)->whereRelation('classroom.schoolYear', 'active', 1);
    }
}
