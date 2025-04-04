<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'created_by',
        'user_id',  // Add user_id to fillable array
    ];

    // Define the relationship between tickets and users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
