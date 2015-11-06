<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use VTalbot\Markdown\Facades\Markdown;

class Staff extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $sluggable = [
        'build_from'      => 'display_name',
        'save_to'         => 'slug',
        'unique'          => true,
        'include_trashed' => true
    ];

    public function campus() {
        return $this->belongsTo('FaithPromise\Shared\Models\Campus');
    }

    public function teams() {
        return $this->belongsToMany('FaithPromise\Shared\Models\Team');
    }

    public function ministries() {
        return $this->belongsToMany('FaithPromise\Shared\Models\Ministry', 'staff_ministry');
    }

    public function getUrlAttribute() {
        return '/staff/' . $this->slug;
    }

    public function getImageAttribute() {
        $img = 'images/staff/' . $this->slug . '-square.jpg';
        return asset_exists($img) ? $img : 'images/staff/default-square.jpg';
    }

    public function getBioAttribute() {
        return trim(Markdown::string($this->getOriginal('bio')));
    }

    public function getEightBitPathAttribute() {
        return 'images/staff/' . $this->slug . '-8bit-square.jpg';
    }

    public function getHasSocialLinksAttribute() {
        return !empty($this->facebook) OR !empty($this->twitter) OR !empty($this->instagram);
    }

    public function getFacebookUrlAttribute() {
        return empty($this->facebook) ? null : facebook_url($this->facebook);
    }

    public function getTwitterUrlAttribute() {
        return empty($this->twitter) ? null : twitter_url($this->twitter);
    }

    public function getInstagramUrlAttribute() {
        return empty($this->instagram) ? null : instagram_url($this->instagram);
    }

    public function getProfileNameAttribute() {
        return $this->display_name;
    }

    public function getNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getProfileTitleAttribute() {
        return $this->title;
    }

    public function getProfileUrlAttribute() {
        return $this->getUrlAttribute();
    }

    public function getProfileImageAttribute() {
        return $this->getImageAttribute();
    }

}
