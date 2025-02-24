<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberTeam extends Model
{
    protected $guarded = ['id'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
