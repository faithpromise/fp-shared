<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Interfaces\CardInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use FaithPromise\Shared\Traits\ExpiredTrait;
use FaithPromise\Shared\Traits\PublicTrait;
use FaithPromise\Shared\Traits\PublishedTrait;
use Illuminate\Database\Eloquent\Model;

class Job extends Model implements CardInterface {

    use PublicTrait;
    use PublishedTrait;
    use ExpiredTrait;
    use SluggableTrait;

    protected $dates = ['publish_at', 'expires_at', 'created_at', 'updated_at'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
        'unique'     => false
    ];

    public function getCardTitleAttribute() {
        return $this->title;
    }

    public function getCardSubtitleAttribute() {
        return null;
    }

    public function getCardTextAttribute() {
        return $this->excerpt;
    }

    public function getCardImageAttribute() {
        return null;
    }

    public function getCardUrlAttribute() {
        return route('jobDetail', ['job' => $this->slug]);
    }

    public function getCardUrlTextAttribute() {
        return 'More Details';
    }
}
