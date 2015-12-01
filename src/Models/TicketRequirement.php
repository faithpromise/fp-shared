<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketRequirement extends Model {

    use SoftDeletes;

    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['parent_id', 'zendesk_ticket_id', 'title', 'body', 'sort', 'created_by'];
    protected $hidden = ['created_at', 'updated_at'];

    public function author() {
        return $this->hasOne(Staff::class, 'id', 'completed_by');
    }

}
