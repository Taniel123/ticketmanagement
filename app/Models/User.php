<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // Added role column
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define the constant roles for users.
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_SUPPORT = 'support';

    /**
     * Get the role of the user.
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a support staff.
     */
    public function isSupport()
    {
        return $this->role === self::ROLE_SUPPORT;
    }

    /**
     * Check if the user is a regular user.
     */
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }
}

