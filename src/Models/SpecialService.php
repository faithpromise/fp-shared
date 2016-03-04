<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialService extends Model {

    protected $table = 'special_services';
    protected $dates = ['service_day', 'created_at', 'updated_at'];
    protected $casts = [
        'service_times' => 'array'
    ];

    public function campus() {
        return $this->hasOne(Campus::class, 'id', 'campus_id');
    }

}
