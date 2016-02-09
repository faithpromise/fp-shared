<?php

namespace FaithPromise\Shared\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use FaithPromise\Shared\Scopes\PostTypeEventScope;

class Event extends Post implements SluggableInterface {

    public static function boot() {
        static::addGlobalScope(new PostTypeEventScope);
        parent::boot();
    }

    public function calendar() {
        return $this->hasMany('FaithPromise\Shared\Models\CalendarEvent', 'event_number', 'calendar_event_number');
    }

}
