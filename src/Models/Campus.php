<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Campus extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $sluggable = [
        'build_from'      => 'name',
        'save_to'         => 'slug',
        'unique'          => true,
        'include_trashed' => true
    ];

    public function staff() {
        /* Must refer to foreign key because StudentCampus extends this model */
        return $this->hasMany('FaithPromise\Shared\Models\Staff', 'campus_id')->orderBy('sort');
    }

    public function getFullNameAttribute() {
        return $this->name . ' Campus';
    }

    public function getUrlAttribute() {
        return '/locations/' . $this->slug;
    }

    public function getImageAttribute() {
        return 'images/campuses/' . $this->slug . '-wide.jpg';
    }

    public function getTimesAttribute() {
        $times = json_decode($this->getOriginal('times'));
        if (property_exists($times, 'normal')) {
            return $times->normal;
        }
        return null;
    }

    public function getCardLinkIdAttribute() {
        return 'to_campus_' . $this->slug . '_from_card';
    }

    public function getCardTitleAttribute() {
        return $this->location;
    }

    public function getCardSubtitleAttribute() {
        if ($this->opened_at) {
            return $this->full_name;
        } else {
            return 'Coming Soon';
        }
    }

    public function getCardTextAttribute() {
        return str_replace('; ', '<br>', $this->times);
    }

    public function getCardImageAttribute() {
        return $this->getImageAttribute();
    }

    public function getCardUrlTextAttribute() {
        return 'More Details';
    }

    public function getCardUrlAttribute() {
        return $this->getUrlAttribute();
    }
}