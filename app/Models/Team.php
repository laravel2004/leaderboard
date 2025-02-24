<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $guarded = ['id'];

    public function members()
    {
        return $this->hasMany(MemberTeam::class);
    }

    public function userMembers()
    {
        return $this->hasMany(MemberTeam::class)->with('participant');
    }

    public function contest()
    {
        return $this->belongsTo(Contest::class);
    }
}
