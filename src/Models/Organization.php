<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Organization extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $sluggable = [
        'build_from'      => 'name',
        'save_to'         => 'slug',
        'unique'          => true,
        'include_trashed' => true
    ];

    public function getUrlAttribute() {
        return route('localOutreachOrganization', ['slug' => $this->slug]);
    }

    public function getImageAttribute() {
        $img = 'images/missions/local/' . $this->slug . '-tall.png';
        $img = empty($this->getOriginal('image')) ? $img : $this->getOriginal('image');

        return asset_exists($img) ? $img : null;
    }

    public function getExcerptAttribute() {
        $excerpt = $this->getOriginal('excerpt');

        return strlen($excerpt) ? $excerpt : excerpt($this->description, 110);
    }

    public function getCardLinkIdAttribute() {
        return 'to_organization' . $this->slug . '_from_card';
    }

    public function getCardTitleAttribute() {
        return $this->name;
    }

    public function getCardSubtitleAttribute() {
        return $this->location;
    }

    public function getCardTextAttribute() {
        return $this->excerpt;
    }

    public function getCardImageAttribute() {
        return $this->image;
    }

    public function getCardUrlTextAttribute() {
        return 'More Details';
    }

    public function getCardUrlAttribute() {
        return $this->getUrlAttribute();
    }

    public function scopeLocalOutreach($query) {
        $query->where('type', '=', 'local-outreach')->orderBy('sort');
    }
}
