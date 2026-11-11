<?php

namespace App\Models;

use App\Traits\Models\BelongsToSchool;
use App\Traits\Models\MorphTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelHasRfid extends Model
{
    use HasFactory, HasUuids, MorphTo;

    protected $guarded = ['id'];
}   
