<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\EventEndsTrait;

class MissionTrip extends Model
{

    use EventEndsTrait;

    protected $dates = ['starts_at', 'ends_at', 'created_at', 'updated_at'];

    public function missionlocation() {
        return $this->belongsTo('FaithPromise\Shared\Models\MissionLocation');
    }

    public function getIsHappeningNowAttribute() {
        return
            !is_null($this->starts_at)
            && $this->starts_at->isPast()
            && !is_null($this->ends_at)
            && $this->ends_at->isFuture();
    }

    public function getDateRangeAttribute() {

        if (is_null($this->starts_at) OR is_null($this->ends_at)) {
            return strlen($this->dates_text) ? $this->dates_text : '';
        }

        if ($this->starts_at->month != $this->ends_at->month) {
            return $this->starts_at->format('M j') . ' - ' . $this->ends_at->format('M j, Y');
        } else {
            return $this->starts_at->format('M j') . ' - ' . $this->ends_at->format('j, Y');
        }
    }

}
