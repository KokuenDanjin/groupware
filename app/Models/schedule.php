<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class schedule extends Model
{
    function users():BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
