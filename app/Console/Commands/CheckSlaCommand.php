<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Services\NotificationService;
use App\Services\SlaService;
use Illuminate\Console\Command;

class CheckSlaCommand extends Command
{
    protected $signature = 'sla:check';
    protected $description = 'Cek SLA tiket & kirim notifikasi warning/breach';

    public function handle(SlaService $sla, NotificationService $notif): int
    {
        $activeStatuses = ['new', 'assigned', 'in_progress', 'pending', 'reopened'];

        $tickets = Ticket::whereIn('status', $activeStatuses)
            ->whereNotNull('sla_resolution_deadline')
            ->get();

        $warningCount = 0;
        $breachCount = 0;

        foreach ($tickets as $ticket) {
            $percent = $sla->slaPercentage($ticket->created_at, $ticket->sla_resolution_deadline);

            // Cek breach
            if (now()->gte($ticket->sla_resolution_deadline)) {
                if (!$ticket->is_sla_breached) {
                    $ticket->update(['is_sla_breached' => true]);
                    $notif->slaBreach($ticket);
                    $breachCount++;
                    $this->warn("BREACH: {$ticket->ticket_number}");
                }
                continue;
            }

            // Cek warning (>=80%)
            if ($percent >= 80) {
                // Cek sudah pernah dikirim warning belum (dalam 1 jam terakhir)
                $alreadyWarned = \App\Models\AppNotification::where('user_id', $ticket->requester_id)
                    ->where('type', 'sla_warning')
                    ->where('url', route('tickets.show', $ticket))
                    ->where('created_at', '>=', now()->subHour())
                    ->exists();

                if (!$alreadyWarned) {
                    $notif->slaWarning($ticket, (int) $percent);
                    $warningCount++;
                    $this->info("WARNING {$percent}%: {$ticket->ticket_number}");
                }
            }
        }

        $this->info("✅ Selesai. Warning: {$warningCount}, Breach: {$breachCount}");
        return Command::SUCCESS;
    }
}