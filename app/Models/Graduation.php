<?php

namespace App\Models;

use App\Traits\Models\BelongsToSchoolYear;
use App\Traits\Models\HasManyGraduationDetail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Graduation extends Model
{
    use HasUuids,
        SoftDeletes,
        BelongsToSchoolYear,
        HasManyGraduationDetail;

    protected $guarded = ['id'];
    protected $fillable = [
        'school_year_id',
        'announcement_date',
        'additional_file',
    ];

    protected $casts = [
        'announcement_date' => 'datetime',
    ];

    public function getAdditionalFileNameAttribute(): ?string
    {
        if (!$this->additional_file) {
            return null;
        }

        return basename($this->additional_file);
    }

    public function getAdditionalFileSizeAttribute(): ?string
    {
        if (!$this->additional_file || !Storage::exists($this->additional_file)) {
            return null;
        }

        $size = Storage::size($this->additional_file);

        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        }

        return number_format($size / 1024, 2) . ' KB';
    }

    public function getAdditionalFileUrlAttribute(): ?string
    {
        if (!$this->additional_file) {
            return null;
        }

        return Storage::url($this->additional_file);
    }
}
