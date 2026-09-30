<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketComment;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketActionController extends Controller
{
    /**
     * Assign tiket ke IT Support (Super Admin / Supervisor)
     */
    public function assign(Request $request, Ticket $ticket, NotificationService $notif)
    {
        if (!auth()->user()->hasAnyRole(['Super Admin', 'Supervisor'])) {
            abort(403);
        }

        $request->validate([
            'assigned_to_id' => ['required', 'exists:users,id'],
        ]);

        $assignee = User::findOrFail($request->assigned_to_id);

        if (!$assignee->hasRole('IT Support')) {
            return back()->with('error', 'User bukan IT Support.');
        }

        $oldAssignee = $ticket->assigned_to_id;

        $ticket->update([
            'assigned_to_id' => $assignee->id,
            'status' => $ticket->status === 'new' ? 'assigned' : $ticket->status,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'assigned',
            'old_value' => $oldAssignee ? (string) $oldAssignee : null,
            'new_value' => (string) $assignee->id,
            'description' => "Tiket di-assign ke {$assignee->name}",
        ]);

        // Kirim notifikasi ke assignee
        $notif->ticketAssigned($ticket);

        return back()->with('success', "Tiket di-assign ke {$assignee->name}.");
    }

    /**
     * Ambil tiket sendiri (self-assign oleh IT Support)
     */
    public function take(Ticket $ticket, NotificationService $notif)
    {
        $user = auth()->user();

        if (!$user->hasRole('IT Support')) {
            abort(403);
        }

        if ($ticket->assigned_to_id && $ticket->assigned_to_id !== $user->id) {
            return back()->with('error', 'Tiket sudah ditangani teknisi lain.');
        }

        if (in_array($ticket->status, ['closed', 'cancelled'])) {
            return back()->with('error', 'Tiket sudah ditutup/dibatalkan.');
        }

        $ticket->update([
            'assigned_to_id' => $user->id,
            'status' => $ticket->status === 'new' ? 'assigned' : $ticket->status,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'assigned',
            'description' => "Tiket diambil oleh {$user->name}",
        ]);

        $notif->ticketAssigned($ticket);

        return back()->with('success', 'Tiket berhasil diambil.');
    }

    /**
     * Update status tiket
     */
    public function updateStatus(Request $request, Ticket $ticket, NotificationService $notif)
    {
        $user = auth()->user();

        if (!$user->hasAnyRole(['Super Admin', 'IT Support'])) {
            abort(403);
        }

        if ($user->hasRole('IT Support') && $ticket->assigned_to_id !== $user->id) {
            abort(403, 'Anda bukan penanggung jawab tiket ini.');
        }

        $request->validate([
            'status' => ['required', 'in:in_progress,pending,resolved,closed,reopened'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $oldStatus = $ticket->status;

        // Validasi transisi status
        $allowedTransitions = [
            'new' => ['in_progress', 'pending', 'resolved'],
            'assigned' => ['in_progress', 'pending', 'resolved'],
            'in_progress' => ['pending', 'resolved'],
            'pending' => ['in_progress', 'resolved'],
            'resolved' => ['closed', 'reopened'],
            'closed' => ['reopened'],
            'reopened' => ['in_progress', 'pending', 'resolved'],
        ];

        if (!in_array($request->status, $allowedTransitions[$oldStatus] ?? [])) {
            return back()->with('error', "Transisi status {$oldStatus} → {$request->status} tidak diizinkan.");
        }

        // Wajib isi note kalau resolve
        if ($request->status === 'resolved' && empty($request->note)) {
            return back()->with('error', 'Catatan solusi wajib diisi saat menyelesaikan tiket.');
        }

        $updateData = ['status' => $request->status];

        if ($request->status === 'in_progress' && !$ticket->responded_at) {
            $updateData['responded_at'] = now();
        }
        if ($request->status === 'resolved') {
            $updateData['resolved_at'] = now();
            $updateData['resolution_note'] = $request->note;
        }
        if ($request->status === 'closed') {
            $updateData['closed_at'] = now();
        }
        if ($request->status === 'reopened') {
            $updateData['resolved_at'] = null;
            $updateData['closed_at'] = null;
        }

        $ticket->update($updateData);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'status_changed',
            'old_value' => $oldStatus,
            'new_value' => $request->status,
            'description' => $request->note,
        ]);

        // Notifikasi ke requester
        $notif->ticketStatusChanged($ticket, $oldStatus, $request->status);

        return back()->with('success', 'Status tiket berhasil diubah.');
    }

    /**
     * Tambah komentar
     */
    public function comment(Request $request, Ticket $ticket, NotificationService $notif)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'is_internal_note' => ['nullable', 'boolean'],
        ]);

        $user = auth()->user();
        $isInternal = $request->boolean('is_internal_note')
                      && $user->hasAnyRole(['Super Admin', 'IT Support']);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'body' => $request->body,
            'is_internal_note' => $isInternal,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'commented',
            'description' => $isInternal ? 'Internal note ditambahkan' : 'Komentar ditambahkan',
        ]);

        $notif->ticketCommented($ticket, $user, $isInternal);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Reopen tiket
     */
    public function reopen(Request $request, Ticket $ticket, NotificationService $notif)
    {
        $user = auth()->user();

        if ($ticket->requester_id !== $user->id && !$user->hasRole('Super Admin')) {
            abort(403);
        }

        if (!in_array($ticket->status, ['resolved', 'closed'])) {
            return back()->with('error', 'Hanya tiket resolved/closed yang bisa di-reopen.');
        }

        $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $oldStatus = $ticket->status;

        $ticket->update([
            'status' => 'reopened',
            'resolved_at' => null,
            'closed_at' => null,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'action' => 'reopened',
            'old_value' => $oldStatus,
            'new_value' => 'reopened',
            'description' => $request->reason,
        ]);

        $notif->ticketStatusChanged($ticket, $oldStatus, 'reopened');

        return back()->with('success', 'Tiket berhasil di-reopen.');
    }

    /**
     * Hapus lampiran
     */
    public function deleteAttachment(Ticket $ticket, $attachmentId)
    {
        $attachment = $ticket->attachments()->findOrFail($attachmentId);

        if (!auth()->user()->hasAnyRole(['Super Admin', 'IT Support'])
            && $attachment->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        TicketActivity::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'action' => 'attachment_deleted',
            'description' => "Lampiran dihapus: {$attachment->file_name}",
        ]);

        return back()->with('success', 'Lampiran dihapus.');
    }

    /**
     * Download lampiran
     */
    public function downloadAttachment(Ticket $ticket, $attachmentId)
    {
        $attachment = $ticket->attachments()->findOrFail($attachmentId);

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}