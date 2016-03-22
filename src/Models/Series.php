<?php

namespace FaithPromise\Shared\Models;

use Carbon\Carbon;
use FaithPromise\Shared\Traits\SiteContextTrait;
use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\PublishedTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

/**
 * Class Series
 * @package FaithPromise\Shared\Models
 *
 * @method static \Illuminate\Support\Collection currentSeries()
 */
class Series extends Model implements SluggableInterface {

    use PublishedTrait;
    use SluggableTrait;
    use SiteContextTrait;

    protected $dates = ['starts_at', 'publish_at', 'created_at', 'updated_at'];
    protected $casts = [
        'alignment_resources' => 'array'
    ];

    protected $sluggable = [
        'build_from'      => 'title',
        'save_to'         => 'slug',
        'unique'          => true
    ];

    public function videos() {
        return $this->hasMany('FaithPromise\Shared\Models\Video')->orderBy('sermon_date');
    }

    public function promo() {
        return $this->hasOne('FaithPromise\Shared\Models\Video')->where('type', '=', 'promo');
    }

    public function alignmentResources() {
        return $this->hasMany(AlignmentResource::class)->orderBy('sort');
    }

    public function getImageAttribute() {
        if ($this->site === 'fpstudents') {
            return 'images/fpstudents/series/' . $this->slug . '-wide.jpg';
        }
        return 'images/series/' . $this->slug . '-wide.jpg';
    }

    public function getHomeImageAttribute() {
        if ($this->site === 'fpstudents') {
            return 'images/fpstudents/series/' . $this->slug . '-home-wide.jpg';
        }
        return 'images/series/' . $this->slug . '-home-wide.jpg';
    }

    public function getUrlAttribute() {
        return route('series', ['series' => $this->slug]);
    }

    public function getWhenAttribute() {
        return $this->starts_at->format('M Y');
    }

    public function scopeCurrentSeries($query) {
        $query->orderBy('publish_at', 'desc')->take(1);
    }

    public function getDate($next_series_starts) {

        $today = Carbon::today();
        $diff_in_days = $today->diffInDays($this->starts_at, false);

        // Next series has already started, just show the date for this series
        if (!is_null($next_series_starts) AND $next_series_starts->gt($today)) {
            return $this->starts_at->format('M, Y');
        }

        // Series started before today
        if ($diff_in_days < 0) {
            return 'Current series';
        }

        // Series started today
        if ($diff_in_days === 0) {
            return 'Begins today!';
        }

        // 6 days away (it's Sunday before it starts (on Saturday)
        if ($diff_in_days === 6) {
            return 'Begins in 1 week';
        }

        // More than a week away
        if ($diff_in_days >= 7) {
            return 'Begins in ' . $this->starts_at->diffInWeeks($today) . ' week' . ($this->starts_at->diffInWeeks($today) > 1 ? 's' : '');
        }

        return 'Begins this weekend';
    }

}
