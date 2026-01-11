<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }
    protected $fillable = ["name", "email", "no_telepon","password", "role", "status"];
    protected $hidden = ["password"];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, "assigned_to");
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    
    public function divisi()
    {
        return $this->hasMany(UserDivisi::class);
    }
}
