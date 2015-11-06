<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Ministry extends Model implements SluggableInterface {

    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at'];

    protected $sluggable = [
        'build_from'      => 'name',
        'save_to'         => 'slug',
        'unique'          => true,
        'include_trashed' => true
    ];

    public function staff() {
        return $this->belongsToMany('FaithPromise\Shared\Models\Staff', 'staff_ministry')->orderBy('sort');
    }

    public function events() {
        return $this->hasMany('FaithPromise\Shared\Models\Event')->orderBy('sort');
    }

    public function volunteer_positions() {
        return $this->hasMany('FaithPromise\Shared\Models\VolunteerPosition');
    }
}
