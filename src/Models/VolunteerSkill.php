<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerSkill extends Model {

    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['title', 'description'];

    public function volunteer_positions() {
        return $this->belongsToMany('FaithPromise\Shared\Models\VolunteerPosition', 'volunteer_positions_skills');
    }

}
