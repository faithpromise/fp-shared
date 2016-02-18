<?php

namespace FaithPromise\Shared\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use FaithPromise\Shared\Traits\EventEndsTrait;

class CalendarEvent extends Model
{

    use EventEndsTrait;

    protected $primary = 'id';
    protected $increments = false;
    protected $dates = ['starts_at', 'ends_at', 'created_at', 'updated_at'];

    public function calendar()
    {
        return $this->belongsTo('FaithPromise\Shared\Models\Event', 'calendar_event_number', 'event_number');
    }

    public function scopeWithinRange($query, $start, $end)
    {
        return $query
            ->where('starts_at', '>', $start)
            ->where('ends_at', '<', $end)
            ->orderBy('starts_at');
    }

    public static function monthHasEvents($year, $month)
    {
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $num = (new static)->where('starts_at', '>', $start)->where('ends_at', '<', $end)->count();
        return $num > 0;
    }

}
