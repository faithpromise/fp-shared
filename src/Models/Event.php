<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\PublishedTrait;
use FaithPromise\Shared\Traits\ExpiredTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Event extends Model implements SluggableInterface {

    use PublishedTrait;
    use ExpiredTrait;
    use SluggableTrait;

    protected $dates = ['publish_at', 'expires_at', 'created_at', 'updated_at'];

    protected $sluggable = [
        'build_from'      => 'title',
        'save_to'         => 'slug',
        'unique'          => false
    ];

    public function ministry() {
        return $this->belongsTo('FaithPromise\Shared\Models\Ministry');
    }

    public function calendar() {
        return $this->hasMany('FaithPromise\Shared\Models\CalendarEvent', 'event_number', 'calendar_event_number');
    }

    public function getImageAttribute() {

        if (empty($this->getOriginal('image'))) {
            return 'images/events/' . $this->slug . '-tall.jpg';
        }

        return $this->getOriginal('image');
    }

    public function getOriginalUrlAttribute() {
        return $this->getOriginal('url');
    }

    public function getUrlAttribute() {
        $url = $this->getOriginal('url');
        return strlen($url) ? $url : route('event', ['event' => $this->slug]);
    }

    public function getCardTitleAttribute() {
        return $this->title;
    }

    public function getCardSubtitleAttribute() {
        return $this->dates_text;
    }

    public function getCardTextAttribute() {
        return $this->excerpt;
    }

    public function getCardImageAttribute()
    {
        return $this->image;
    }

    public function getCardUrlTextAttribute() {
        return 'More Details';
    }

    public function getCardUrlAttribute() {
        return $this->getUrlAttribute();
    }

}
