<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTransferRequest extends Model
{
    protected $fillable = [
        'ticket_id',
        'requested_by',
        'from_technician_id',
        'to_technician_id',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function fromTechnician()
    {
        return $this->belongsTo(User::class, 'from_technician_id');
    }

    public function toTechnician()
    {
        return $this->belongsTo(User::class, 'to_technician_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
