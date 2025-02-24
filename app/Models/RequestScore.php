<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestScore extends Model
{
    protected $guarded = ['id'];

    public function contest()
    {
        return $this->belongsTo(Contest::class, 'contest_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

}
