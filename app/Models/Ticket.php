<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\TicketNotification;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'created_by'
    ];

    protected static function boot()
    {
        parent::boot();

        // Send notification when ticket is created
        static::created(function ($ticket) {
            $ticket->user->notify(new TicketNotification($ticket, 'created', auth()->user()));
        });

        // Send notification when ticket is updated
        static::updated(function ($ticket) {
            if ($ticket->isDirty('status')) {
                $ticket->user->notify(new TicketNotification($ticket, 'status_updated', auth()->user()));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}