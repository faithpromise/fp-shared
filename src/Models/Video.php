<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Traits\SiteContextTrait;
use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\PublishedTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use VTalbot\Markdown\Facades\Markdown;

class Video extends Model implements SluggableInterface {

    use PublishedTrait;
    use SluggableTrait;
    use SiteContextTrait;

    protected $dates = ['sermon_date', 'publish_at', 'created_at', 'updated_at'];

    protected $sluggable = [
        'build_from'      => 'title',
        'save_to'         => 'slug',
        'unique'          => false
    ];

    public function series() {
        return $this->belongsTo('FaithPromise\Shared\Models\Series');
    }

    public function speaker() {
        return $this->belongsTo('FaithPromise\Shared\Models\Staff', 'speaker_id', 'id');
    }

    public function getImageAttribute() {
        $series = $this->Series;
        if ($series->is_official) {
            return $series->image;
        } else if ($this->Speaker) {
            return $this->Speaker->image;
        } else {
            return 'images/general/default-tall.jpg';
        }
    }

    public function getUrlAttribute() {
        return route('seriesVideo', ['series' => $this->Series->slug, 'video' => $this->slug]);
    }

    public function getSpeakerDisplayNameAttribute() {
        if (strlen($this->speaker_name)) {
            return $this->speaker_name;
        }
        if (! is_null($this->Speaker)) {
            return $this->Speaker->display_name;
        }
        return '';
    }

    public function getSermonDateFormattedAttribute() {
        return ($this->type != 'sermon' || is_null($this->sermon_date)) ? '' : $this->sermon_date->format('M d, Y');
    }

    public function getVideoDateAttribute() {
        return ($this->type != 'sermon' || is_null($this->sermon_date)) ? $this->publish_at : $this->sermon_date;
    }

    public function getHasResourcesAttribute() {
        return !empty($this->getOriginal('resources'));
    }

    public function getResourcesAttribute() {
        return trim(Markdown::string($this->getOriginal('resources')));
    }

}