<?php

namespace App\Models;

use App\Traits\Models\BelongsToClassroom;
use App\Traits\Models\BelongsToEmployee;
use App\Traits\Models\BelongsToSubject;
use App\Traits\Models\BelongsToTopic;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory,
        HasUuids,
        SoftDeletes,
        BelongsToTopic;

    protected $table = 'modules';
    public $incrementing = false;
    protected $keyType = 'char';

    protected $fillable = [
        'title',
        'content',
        'link',
        'topic_id',
        'link_type',
    ];
}
