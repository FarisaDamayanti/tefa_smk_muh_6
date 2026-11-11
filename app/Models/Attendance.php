<?php

namespace App\Models;

use App\Enums\AttendanceEnum;
use App\Traits\Models\MorphTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, HasUuids, MorphTo;

    public $casts = [
        'status' => AttendanceEnum::class
    ];
    protected $guarded = ['id'];

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'model_type',
        'model_id',
        'point',
        'status',
        'proof',
        'checkin',
        'checkout',
    ];
}
