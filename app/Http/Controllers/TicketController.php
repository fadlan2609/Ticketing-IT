<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketAttachment;
use App\Models\User;
use App\Services\NotificationService;
use App\Services\SlaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    /**
     * Daftar tiket dengan filter berdasarkan role
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Ticket::with(['category', 'requester', 'assignee', 'branch']);

        // Filter berdasarkan role
        if ($user->hasRole('Requester')) {
            $query->where('requester_id', $user->id);
        } elseif ($user->hasRole('IT Support')) {
            $query->where(function ($q) use ($user) {
                $q->where('assigned_to_id', $user->id)
                  ->orWhere(function ($q2) {
                      $q2->whereNull('assigned_to_id')
                         ->whereNotIn('status', ['closed', 'cancelled']);
                  });
            });
        } elseif ($user->hasRole('Supervisor')) {
            if ($user->branch_id) {
                $query->where('branch_id', $user->branch_id);
            }
        }
        // Super Admin & Auditor lihat semua

        // Filter
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }
        if ($branchId = $request->input('branch_id')) {
            $query->where('branch_id', $branchId);
        }
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort default
        $query->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
              ->orderBy('sla_resolution_deadline', 'asc')
              ->orderBy('created_at', 'desc');

        $tickets = $query->paginate(15)->withQueryString();

        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('tickets.index', compact('tickets', 'categories', 'branches'));
    }

    /**
     * Form buat tiket
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('tickets.create', compact('categories', 'branches'));
    }

    /**
     * Simpan tiket baru
     */
    public function store(StoreTicketRequest $request, SlaService $sla, NotificationService $notif)
    {
        $data = $request->validated();
        $user = $request->user();

        DB::beginTransaction();
        try {
            $ticket = Ticket::create([
                'ticket_number' => Ticket::generateTicketNumber(),
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'priority' => $data['priority'],
                'requester_id' => $user->id,
                'branch_id' => $data['branch_id'] ?? $user->branch_id,
                'status' => 'new',
                'sla_response_deadline' => $sla->calculateDeadline($data['priority'], 'response'),
                'sla_resolution_deadline' => $sla->calculateDeadline($data['priority'], 'resolution'),
            ]);

            // Upload lampiran
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $this->saveAttachment($ticket, $file, $user->id);
                }
            }

            // Catat aktivitas
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'action' => 'created',
                'description' => 'Tiket dibuat',
            ]);

            DB::commit();

            // Kirim notifikasi ke IT Support & Supervisor
            $notif->ticketCreated($ticket);

            return redirect()->route('tickets.show', $ticket)
                ->with('success', "Tiket {$ticket->ticket_number} berhasil dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal membuat tiket: ' . $e->getMessage());
        }
    }

    /**
     * Detail tiket
     */
    public function show(Ticket $ticket)
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->load([
            'category', 'requester', 'assignee', 'branch',
            'attachments.user', 'activities.user',
        ]);

        $user = auth()->user();

        // Internal note hanya untuk tim IT
        if ($user->hasAnyRole(['Super Admin', 'IT Support', 'Supervisor', 'Auditor'])) {
            $comments = $ticket->comments()->with('user')->get();
        } else {
            $comments = $ticket->publicComments()->with('user')->get();
        }

        $itSupports = User::role('IT Support')->where('is_active', true)->get();

        return view('tickets.show', compact('ticket', 'comments', 'itSupports'));
    }

    /**
     * Form edit tiket
     */
    public function edit(Ticket $ticket)
    {
        $this->authorizeTicketEdit($ticket);

        $categories = Category::where('is_active', true)->get();
        $branches = Branch::where('is_active', true)->get();

        return view('tickets.edit', compact('ticket', 'categories', 'branches'));
    }

    /**
     * Update tiket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket, SlaService $sla)
    {
        $this->authorizeTicketEdit($ticket);

        $oldPriority = $ticket->priority;

        $ticket->update($request->validated());

        // Kalau priority berubah, recalculate SLA
        if ($oldPriority !== $request->priority) {
            $ticket->update([
                'sla_response_deadline' => $sla->calculateDeadline($request->priority, 'response', $ticket->created_at),
                'sla_resolution_deadline' => $sla->calculateDeadline($request->priority, 'resolution', $ticket->created_at),
            ]);

            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'action' => 'priority_changed',
                'old_value' => $oldPriority,
                'new_value' => $request->priority,
                'description' => 'Prioritas tiket diubah',
            ]);
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Tiket berhasil diperbarui.');
    }

    /**
     * Hapus tiket (hanya Super Admin)
     */
    public function destroy(Ticket $ticket)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        // Hapus semua lampiran
        foreach ($ticket->attachments as $att) {
            Storage::disk('public')->delete($att->file_path);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }

    /**
     * Simpan file lampiran
     */
    private function saveAttachment(Ticket $ticket, $file, int $userId): void
    {
        $originalName = $file->getClientOriginalName();
        $fileName = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('ticket-attachments/' . $ticket->id, $fileName, 'public');

        TicketAttachment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $userId,
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Authorization: akses lihat tiket
     */
    private function authorizeTicketAccess(Ticket $ticket): void
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['Super Admin', 'Auditor'])) return;

        if ($user->hasRole('IT Support')) return;

        if ($user->hasRole('Supervisor') && $ticket->branch_id === $user->branch_id) return;

        if ($ticket->requester_id === $user->id) return;

        abort(403, 'Anda tidak memiliki akses ke tiket ini.');
    }

    /**
     * Authorization: edit tiket
     */
    private function authorizeTicketEdit(Ticket $ticket): void
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) return;

        // Requester hanya bisa edit tiket status "new"
        if ($ticket->requester_id === $user->id && in_array($ticket->status, ['new'])) {
            return;
        }

        // IT Support yang menangani
        if ($user->hasRole('IT Support') && $ticket->assigned_to_id === $user->id) return;

        abort(403, 'Anda tidak dapat mengedit tiket ini.');
    }
}