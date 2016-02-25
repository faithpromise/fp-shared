<?php

namespace FaithPromise\Shared\Models;

use FaithPromise\Shared\Scopes\StudentCampusScope;

class StudentCampus extends Campus {

    protected $table = 'campuses';

    public static function boot() {
        static::addGlobalScope(new StudentCampusScope());
        parent::boot();
    }

    public function getCardTextAttribute() {

        if ($this->student_christmas_times) {
            return 'Check here for our regular & special Christmas service times...';
        }

        if ($this->student_easter_times) {
            return 'Check here for our regular & special Easter service times...';
        }

        $formatted = [];

        if (!$this->student_times) {
            return null;
        }

        foreach($this->student_times as $service) {
            $formatted[] = substr($service->day, 0, 3) . ' at ' . $service->formatted_times;
        }
        return implode('<br>', $formatted);
    }

}