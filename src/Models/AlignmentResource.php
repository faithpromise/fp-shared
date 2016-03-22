<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class AlignmentResource extends Model{

    protected $table = 'alignment_resources';

    public function series() {
        return $this->belongsTo(Series::class);
    }

}