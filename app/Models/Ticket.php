<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'description', 'user_id', 'category_id', 'assigned_to', 'status', 'urgensi'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function comments() {
        return $this->hasMany(TicketComment::class);
    }

    public function attachments() {
        return $this->hasMany(TicketAttachment::class);
    }
}
