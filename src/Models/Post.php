<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Traits\SiteContextTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\PublishedTrait;
use FaithPromise\Shared\Traits\ExpiredTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

/**
 * Class Post
 * @package FaithPromise\Shared\Models
 *
 * @property int $id
 * @property string $site
 * @property string $type
 * @property string $slug
 * @property string $title
 * @property string $subtitle
 * @property string $image
 * @property string $excerpt
 * @property string $description
 * @property string $url
 * @property string $url_text
 */
class Post extends Model implements SluggableInterface {

    use PublishedTrait;
    use ExpiredTrait;
    use SluggableTrait;
    use SiteContextTrait;

    protected $table = 'posts';
    protected $dates = ['publish_at', 'expires_at', 'created_at', 'updated_at'];
    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
        'unique'     => false
    ];
    protected $resource;

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->attributes['type'] = $model->type ?: strtolower(class_basename(static::class));
        });
    }

    public function ministry() {
        return $this->belongsTo('FaithPromise\Shared\Models\Ministry');
    }

    public function getImageAttribute() {

        if (empty($this->getOriginal('image'))) {
            return 'images/' . str_plural($this->type) . '/' . $this->slug . '-tall.jpg';
        }

        return $this->getOriginal('image');
    }

    public function getOriginalUrlAttribute() {
        return $this->getOriginal('url');
    }

    public function getUrlAttribute() {
        $url = $this->getOriginal('url');

        return strlen($url) ? $url : route($this->type, ["{$this->type}" => $this->slug]);
    }

    public function getCardLinkIdAttribute() {
        return 'to_' . $this->type . '_' . $this->slug . '_from_card';
    }

    public function getCardTitleAttribute() {
        return $this->title;
    }

    public function getCardSubtitleAttribute() {
        return $this->subtitle;
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
