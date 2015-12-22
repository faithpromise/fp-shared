<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class MissionLocation extends Model implements SluggableInterface {

    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at'];

    protected $sluggable = [
        'build_from'      => 'name',
        'save_to'         => 'slug',
        'unique'          => true
    ];

    public function missionaries() {
        return $this->hasMany('FaithPromise\Shared\Models\Missionary');
    }

    public function missionTrips() {
        return $this->hasMany('FaithPromise\Shared\Models\MissionTrip');
    }

    public function getUrlAttribute() {
        return '/missions/' . $this->slug;
    }

    public function getDatesProseAttribute() {
        $dates = [];

        foreach ($this->missiontrips as $trip) {
            $dates[] = $trip->date_range;
        }

        return count($dates) ? implode('<br>&amp; ', $dates) : 'Dates TBD';
    }

    public function getCardLinkIdAttribute() {
        return 'to_missionLocation' . $this->slug . '_from_card';
    }

    public function getCardTitleAttribute() {
        return $this->name;
    }

    public function getCardSubtitleAttribute() {
        return $this->getDatesProseAttribute();
    }

    public function getCardImageAttribute() {
        return 'images/missions/locations/' . $this->slug . '-tall.jpg';
    }

    public function getCardUrlTextAttribute() {
        return 'Details';
    }

    public function getCardUrlAttribute() {
        return count($this->missiontrips) === 0 ? null : $this->getUrlAttribute();
    }

    public function scopeUpcoming($query) {
        $query->where('is_continual', '=', 1)
            ->orWhereRaw(DB::raw('id in (select mission_location_id from mission_trips where ends_at > NOW() or ends_at IS NULL)'))
            ->with('missiontrips');
    }
}
