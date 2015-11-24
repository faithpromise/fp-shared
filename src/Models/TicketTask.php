<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketTask extends Model {

    use SoftDeletes;

    protected $dates = ['due_at', 'completed_at', 'created_at', 'updated_at'];
    protected $fillable = ['zendesk_ticket_id', 'title', 'due_at', 'completed_at', 'completed_by'];
    protected $hidden = ['created_at', 'updated_at'];

    public function finisher() {
        return $this->hasOne(Staff::class, 'id', 'completed_by');
    }

}
