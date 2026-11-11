<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Models\BelongsToEmployee;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeJournal extends Model
{
    use HasFactory, HasUuids, BelongsToEmployee;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => StatusEnum::class,
    ];
}
