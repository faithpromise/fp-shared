<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Scopes\StudentCampusScope;

class StudentCampus extends Campus {

    protected $table = 'campuses';

    public static function boot() {
        static::addGlobalScope(new StudentCampusScope());
        parent::boot();
    }

    public function getTimesAttribute() {
        $times = json_decode($this->getOriginal('student_times'));
        return is_array($times) ? implode('; ', $times) : '';
    }

}