<?php

namespace App\Models;

use App\Helpers\Params;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject','number_ticket', 'description', 'user_id', 'category_id', 'assigned_to', 'status', 'urgensi'
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

    public function dialihkan(?int $assignedTo = null): void
{
         DB::transaction(function () use ($assignedTo) {

        $this->status = Params::DIALIHKAN;

        if ($assignedTo) {
            $this->assigned_to = $assignedTo;
        }

        $this->save();

        TicketComment::create([
            'ticket_id' => $this->id,
            'user_id'   => $assignedTo,
            'message'   => 'Tiket dialihkan ke pengguna lain.',
        ]);
    });
}
}
