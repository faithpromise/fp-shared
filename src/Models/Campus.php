<?php

namespace FaithPromise\Shared\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Campus extends Model implements SluggableInterface {

    use SoftDeletes;
    use SluggableTrait;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $sluggable = [
        'build_from'      => 'name',
        'save_to'         => 'slug',
        'unique'          => true,
        'include_trashed' => true
    ];

    public function staff() {
        /* Must refer to foreign key because StudentCampus extends this model */
        return $this->hasMany('FaithPromise\Shared\Models\Staff', 'campus_id')->orderBy('sort');
    }

    public function easter_services() {
        return $this->hasMany(EasterService::class);
    }

    public function getFullNameAttribute() {
        return $this->name . ' Campus';
    }

    public function getUrlAttribute() {
        return '/locations/' . $this->slug;
    }

    public function getImageAttribute() {
        return 'images/campuses/' . $this->slug . '-wide.jpg';
    }

    public function getTimesAttribute() {
        return $this->convertTimes();
    }

    public function getStudentTimesAttribute() {
        return $this->convertTimes('student_times');
    }

    public function getFormattedTimesAttribute() {
        return $this->formatServices($this->times);
    }

    public function getFormattedStudentTimesAttribute() {
        return $this->formatServices($this->student_times);
    }

    public function getChristmasTimesAttribute() {
        return $this->convertChristmasTimes();
    }

    public function getEasterTimesAttribute() {
        return $this->convertEasterTimes();
    }

    public function getStudentChristmasTimesAttribute() {
        return $this->convertChristmasTimes('student_times');
    }

    public function getStudentEasterTimesAttribute() {
        return $this->convertEasterTimes('student_times');
    }

    public function getCardLinkIdAttribute() {
        return 'to_campus_' . $this->slug . '_from_card';
    }

    public function getCardTitleAttribute() {
        return $this->location;
    }

    public function getCardSubtitleAttribute() {
        if ($this->opened_at) {
            return $this->full_name;
        } else {
            return 'Coming Soon';
        }
    }

    public function getCardTextAttribute() {

        if ($this->christmas_times) {
            return 'Check here for our regular & special Christmas service times...';
        }

        if ($this->easter_times) {
            return 'Check here for our regular & special Easter service times...';
        }

        $formatted = [];

        if (!$this->times) {
            return null;
        }

        foreach ($this->times as $service) {
            $formatted[] = substr($service->day, 0, 3) . ' at ' . $service->formatted_times;
        }

        return implode('<br>', $formatted);
    }

    public function getCardImageAttribute() {
        return $this->getImageAttribute();
    }

    public function getCardUrlTextAttribute() {
        return 'More Details';
    }

    public function getCardUrlAttribute() {
        return $this->getUrlAttribute();
    }

    protected function convertTimes($table = 'times') {
        $times = json_decode($this->getOriginal($table));

        if (is_object($times) AND property_exists($times, 'normal')) {
            return $this->prepareTimes($times->normal);
        }

        return null;
    }

    protected function convertChristmasTimes($table = 'times') {
        $today = Carbon::today();
        $christmas = $today->copy()->month(12)->day(25)->endOfDay();

        if ($christmas->isFuture() && $christmas->diffInDays($today) <= 45) {
            $times = json_decode($this->getOriginal($table));
            if (property_exists($times, 'christmas') && property_exists($times->christmas, $christmas->year)) {
                return $this->prepareTimes($times->christmas->{$christmas->year});
            }
        }

        return null;
    }

    protected function convertEasterTimes($table = 'times') {
        $today = Carbon::today();
        $easter = Carbon::createFromTimestamp(easter_date($today->year))->endOfDay();

        if ($easter->isFuture() && $easter->diffInDays($today) <= 45) {
            $times = json_decode($this->getOriginal($table));
            if (property_exists($times, 'easter') && property_exists($times->easter, $easter->year)) {
                return $this->prepareTimes($times->easter->{$easter->year});
            }
        }

        return null;
    }

    private function prepareTimes(&$services) {
        foreach ($services as $service) {
            $service->formatted_times = $this->formatTimes($service->times);
        }

        return $services;
    }

    private function formatTimes($times) {

        $formatted_times = array_map(function ($time) {
            if (preg_match('/(\sam)|(\spm)$/', $time) === 1) {
                return '<span class="no-wrap">' . $time . '</span>';
            }
            return $time;
        }, $times);

        $num_items = count($formatted_times);

        if ($num_items === 2) {
            $formatted_times = [implode(' &amp; ', $formatted_times)];
        } else if ($num_items > 2) {
            $formatted_times[$num_items - 1] = '&amp; ' . end($formatted_times);
        }

        return implode(', ', $formatted_times);

    }

    private function formatServices($services) {

        if (!$services) {
            return '';
        }

        $times = array_map(function ($service) {
            return $service->day . ' at ' . $service->formatted_times;
        }, $services);

        return implode(', ', $times);
    }
}