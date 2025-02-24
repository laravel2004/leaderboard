<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $guarded = ['id'];

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
