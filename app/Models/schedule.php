<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class schedule extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'time_type',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'memo',
        'private_flg',
    ];

    function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
