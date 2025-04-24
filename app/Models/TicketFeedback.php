<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class TicketFeedback extends Model
{
    protected $table = 'ticket_feedbacks';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'status_change'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function tableExists()
    {
        return Schema::hasTable('ticket_feedbacks');
    }
}