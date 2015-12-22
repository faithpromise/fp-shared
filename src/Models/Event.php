<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Support\Facades\DB;
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
        'build_from' => 'title',
        'save_to'    => 'slug',
        'unique'     => false
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

    public function getCardLinkIdAttribute() {
        return 'to_event_' . $this->slug . '_from_card';
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

    public function getCardImageAttribute() {
        return $this->image;
    }

    public function getCardUrlTextAttribute() {
        return empty($this->url_text) ? 'More Details' : $this->url_text;
    }

    public function getCardUrlAttribute() {
        return $this->getUrlAttribute();
    }

    public function scopeFeatured($query) {
        return $query->orderBy(DB::raw('`feature_at` <= NOW() desc, `feature_at` desc, `sort`'))->take(3);
    }

}
