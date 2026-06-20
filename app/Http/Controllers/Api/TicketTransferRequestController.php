<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketTransferRequest;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketTransferRequestController extends Controller
{
    private function getAuthUser()
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function store(Request $request, $ticketId)
    {
        $user = $this->getAuthUser();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'to_technician_id' => 'required|exists:users,id',
            'reason' => 'nullable|string',
        ]);

        $ticket = Ticket::findOrFail($ticketId);

        if ($ticket->assigned_to == $request->to_technician_id) {
            return response()->json([
                'message' => 'Teknisi tujuan tidak boleh sama dengan teknisi saat ini.'
            ], 422);
        }

        $existsPending = TicketTransferRequest::where('ticket_id', $ticket->id)
            ->where('status', 'pending')
            ->exists();

        if ($existsPending) {
            return response()->json([
                'message' => 'Masih ada pengajuan pengalihan yang menunggu approval.'
            ], 422);
        }

        $transfer = TicketTransferRequest::create([
            'ticket_id' => $ticket->id,
            'requested_by' => $user->id,
            'from_technician_id' => $ticket->assigned_to,
            'to_technician_id' => $request->to_technician_id,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);
        
            PointService::addPoint(
                        $ticket->assigned_to,
                        -2,
                        'transfer_request',
                        $ticket->id,
                        'Mengajukan pengalihan teknisi'
                    );

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => 'Pengajuan pengalihan teknisi dibuat dan menunggu approval admin.',
        ]);


        return response()->json([
            'message' => 'Pengajuan pengalihan teknisi berhasil dibuat.',
            'data' => $transfer
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $this->getAuthUser();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $query = TicketTransferRequest::with([
            'ticket',
            'requester',
            'fromTechnician',
            'toTechnician',
            'approver',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'message' => 'Data pengajuan pengalihan teknisi berhasil diambil.',
            'data' => $query->paginate(10)
        ]);
    }

    public function approve($id)
    {
        $user = $this->getAuthUser();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $transfer = TicketTransferRequest::with([
            'ticket',
            'fromTechnician',
            'toTechnician'
        ])->findOrFail($id);

        if ($transfer->status !== 'pending') {
            return response()->json([
                'message' => 'Pengajuan ini sudah diproses.'
            ], 422);
        }

        DB::transaction(function () use ($transfer, $user) {
            $transfer->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);

            $transfer->ticket->update([
                'assigned_to' => $transfer->to_technician_id,
            ]);

            TicketComment::create([
                'ticket_id' => $transfer->ticket_id,
                'user_id' => $user->id,
                'message' => 'Pengalihan teknisi disetujui oleh admin.',
            ]);
        });

        return response()->json([
            'message' => 'Pengalihan teknisi berhasil disetujui.'
        ]);
    }

    public function reject(Request $request, $id)
    {
        $user = $this->getAuthUser();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $request->validate([
            'rejected_reason' => 'nullable|string',
        ]);

        $transfer = TicketTransferRequest::findOrFail($id);

        if ($transfer->status !== 'pending') {
            return response()->json([
                'message' => 'Pengajuan ini sudah diproses.'
            ], 422);
        }

        $transfer->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejected_reason' => $request->rejected_reason,
        ]);

        TicketComment::create([
            'ticket_id' => $transfer->ticket_id,
            'user_id' => $user->id,
            'message' => 'Pengalihan teknisi ditolak oleh admin. Alasan: ' . ($request->rejected_reason ?? '-'),
        ]);

        return response()->json([
            'message' => 'Pengajuan pengalihan teknisi berhasil ditolak.'
        ]);
    }
}