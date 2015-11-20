<?php

namespace FaithPromise\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTask extends Model {

    protected $dates = ['created_at','updated_at'];
    protected $fillable = ['zendesk_ticket_id', 'title', 'due_at'];

}
