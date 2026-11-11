<?php

namespace App\Models;

use App\Enums\GuestBookEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBook extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ['id'];
    public $casts = [
        'status' => GuestBookEnum::class,
    ];
}
