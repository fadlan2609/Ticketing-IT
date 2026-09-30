<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim notifikasi ke user tertentu
     */
    public function sendTo(User $user, array $data): void
    {
        if (!$user->is_active) return;

        AppNotification::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'url' => $data['url'] ?? null,
            'icon' => $data['icon'] ?? '🔔',
        ]);

        // Email via queue (kirim setelah 5 detik agar tidak blocking)
        if (config('mail.default') !== 'log' || true) {
            try {
                \Mail::to($user->email)->queue(new \App\Mail\TicketNotificationMail($user, $data));
            } catch (\Exception $e) {
                Log::error('Email gagal dikirim: ' . $e->getMessage());
            }
        }
    }

    /**
     * Kirim notifikasi ke multiple user
     */
    public function sendToMany($users, array $data): void
    {
        foreach ($users as $user) {
            $this->sendTo($user, $data);
        }
    }

    /**
     * Notifikasi: Tiket baru dibuat → kirim ke IT Support & Supervisor
     */
    public function ticketCreated(Ticket $ticket): void
    {
        $recipients = User::role(['IT Support', 'Supervisor'])
            ->where('is_active', true)
            ->get();

        $this->sendToMany($recipients, [
            'type' => 'ticket_created',
            'title' => "🎫 Tiket Baru: {$ticket->ticket_number}",
            'message' => "{$ticket->requester->name} melaporkan: {$ticket->title}",
            'url' => route('tickets.show', $ticket),
            'icon' => '🎫',
        ]);
    }

    /**
     * Notifikasi: Tiket di-assign ke teknisi
     */
    public function ticketAssigned(Ticket $ticket): void
    {
        if (!$ticket->assignee) return;

        $this->sendTo($ticket->assignee, [
            'type' => 'ticket_assigned',
            'title' => "📌 Tiket Di-assign: {$ticket->ticket_number}",
            'message' => "Tiket \"{$ticket->title}\" di-assign ke Anda.",
            'url' => route('tickets.show', $ticket),
            'icon' => '📌',
        ]);
    }

    /**
     * Notifikasi: Status berubah → ke requester
     */
    public function ticketStatusChanged(Ticket $ticket, string $oldStatus, string $newStatus): void
    {
        $statusLabels = [
            'new' => 'Baru', 'assigned' => 'Ditugaskan',
            'in_progress' => 'Sedang Diproses', 'pending' => 'Pending',
            'resolved' => 'Selesai', 'closed' => 'Ditutup',
            'reopened' => 'Dibuka Kembali', 'cancelled' => 'Dibatalkan',
        ];

        $this->sendTo($ticket->requester, [
            'type' => 'ticket_status_changed',
            'title' => "🔄 Status Tiket: {$ticket->ticket_number}",
            'message' => "Status tiket berubah: " . 
                         ($statusLabels[$oldStatus] ?? $oldStatus) . " → " . 
                         ($statusLabels[$newStatus] ?? $newStatus),
            'url' => route('tickets.show', $ticket),
            'icon' => '🔄',
        ]);
    }

    /**
     * Notifikasi: Komentar baru
     */
    public function ticketCommented(Ticket $ticket, User $commenter, bool $isInternal): void
    {
        if ($isInternal) {
            // Internal note → kirim ke IT Support yang terlibat
            $recipients = collect([$ticket->assignee])->filter();
        } else {
            // Komentar publik → kirim ke requester + assignee (kalau bukan commenter)
            $recipients = collect([$ticket->requester, $ticket->assignee])
                ->filter()
                ->reject(fn($u) => $u->id === $commenter->id);
        }

        $this->sendToMany($recipients, [
            'type' => 'ticket_commented',
            'title' => "💬 Komentar Baru: {$ticket->ticket_number}",
            'message' => "{$commenter->name} mengomentari tiket \"{$ticket->title}\".",
            'url' => route('tickets.show', $ticket),
            'icon' => '💬',
        ]);
    }

    /**
     * Notifikasi: SLA Warning (80%)
     */
    public function slaWarning(Ticket $ticket, int $percent): void
    {
        $recipients = collect([
            $ticket->assignee,
            User::role('Supervisor')->where('branch_id', $ticket->branch_id)->first(),
        ])->filter();

        $this->sendToMany($recipients, [
            'type' => 'sla_warning',
            'title' => "⚠️ SLA Warning {$percent}%: {$ticket->ticket_number}",
            'message' => "Tiket \"{$ticket->title}\" mendekati batas SLA. Segera tangani!",
            'url' => route('tickets.show', $ticket),
            'icon' => '⚠️',
        ]);
    }

    /**
     * Notifikasi: SLA Breach (100%)
     */
    public function slaBreach(Ticket $ticket): void
    {
        $recipients = collect([
            $ticket->assignee,
            User::role('Supervisor')->where('branch_id', $ticket->branch_id)->first(),
            User::role('Super Admin')->first(),
        ])->filter()->unique('id');

        $this->sendToMany($recipients, [
            'type' => 'sla_breach',
            'title' => "🚨 SLA BREACH: {$ticket->ticket_number}",
            'message' => "Tiket \"{$ticket->title}\" MELEWATI batas SLA. Eskalasi diperlukan!",
            'url' => route('tickets.show', $ticket),
            'icon' => '🚨',
        ]);
    }
}