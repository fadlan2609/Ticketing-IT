@extends('layouts.app')

@section('title', $ticket->ticket_number)

@section('breadcrumb')
    <a href="{{ route('tickets.index') }}" class="hover:text-[#18181B] transition">Tiket</a>
    <span class="text-[#D4D4D8]">/</span>
    <span class="text-[#18181B] font-medium">{{ $ticket->ticket_number }}</span>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">

    {{-- Back --}}
    <a href="{{ route('tickets.index') }}"
       class="inline-flex items-center gap-1.5 text-[13px] text-[#71717A] 
              hover:text-[#18181B] transition mb-4">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Tiket
    </a>

    {{-- Header --}}
    <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6 mb-6
                shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
        <div class="flex items-start justify-between gap-4 mb-4 flex-wrap">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="app-ticket-number">{{ $ticket->ticket_number }}</span>
                    <button onclick="copyToClipboard('{{ $ticket->ticket_number }}', this)"
                            class="text-[11px] text-[#71717A] hover:text-[#C8102E] transition">
                        Copy
                    </button>
                </div>
                <h1 class="text-[22px] lg:text-[26px] font-bold text-[#18181B] 
                           tracking-[-0.02em] leading-tight">
                    {{ $ticket->title }}
                </h1>
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <x-tickets.priority :priority="$ticket->priority" />
                    <x-tickets.status :status="$ticket->status" />
                </div>
            </div>

            @can('ticket.edit')
                @if(auth()->user()->hasRole('Super Admin') || 
                    ($ticket->requester_id === auth()->id() && $ticket->status === 'new'))
                    <x-ui.button href="{{ route('tickets.edit', $ticket) }}" variant="secondary" size="sm">
                        Edit
                    </x-ui.button>
                @endif
            @endcan
        </div>

        <x-tickets.sla :ticket="$ticket" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-4 lg:space-y-6">

            {{-- Description --}}
            <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                        shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h2 class="text-[15px] font-semibold text-[#18181B] mb-3">Deskripsi</h2>
                <div class="text-[14px] text-[#52525B] whitespace-pre-line leading-relaxed">
                    {{ $ticket->description }}
                </div>

                @if($ticket->attachments->count() > 0)
                    <div class="mt-4 pt-4 border-t border-[#F0F0F1]">
                        <p class="text-[12px] font-medium text-[#71717A] uppercase tracking-wider mb-2">
                            Lampiran ({{ $ticket->attachments->count() }})
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($ticket->attachments as $att)
                                <a href="{{ route('tickets.attachments.download', [$ticket, $att]) }}"
                                   class="inline-flex items-center gap-2 px-3 py-2 bg-[#FAFAFA] 
                                          hover:bg-[#F0F0F1] border border-[#E4E4E7] 
                                          rounded-lg text-[12px] text-[#52525B] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                    </svg>
                                    <span class="font-medium">{{ $att->file_name }}</span>
                                    <span class="text-[#A1A1AA]">{{ $att->file_size_human }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Resolution --}}
            @if($ticket->resolution_note)
                <div class="bg-[#ECFDF3] border border-[#16A34A]/20 rounded-2xl p-5 lg:p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-[#16A34A]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <h2 class="text-[15px] font-semibold text-[#166534]">Solusi</h2>
                    </div>
                    <p class="text-[14px] text-[#166534] whitespace-pre-line leading-relaxed">
                        {{ $ticket->resolution_note }}
                    </p>
                </div>
            @endif

            {{-- Actions --}}
            @if(auth()->user()->hasAnyRole(['Super Admin', 'IT Support', 'Supervisor']))
                <div class="bg-[#FDECEF] border border-[#C8102E]/20 rounded-2xl p-5 lg:p-6">
                    <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">Aksi</h2>
                    <div class="flex flex-wrap gap-2">

                        @if(auth()->user()->hasRole('IT Support') 
                            && !$ticket->assigned_to_id 
                            && in_array($ticket->status, ['new']))
                            <form action="{{ route('tickets.take', $ticket) }}" method="POST">
                                @csrf
                                <x-ui.button type="submit" size="sm">Ambil Tiket</x-ui.button>
                            </form>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Super Admin', 'Supervisor']))
                            <form action="{{ route('tickets.assign', $ticket) }}" method="POST" 
                                  class="flex gap-2 items-center">
                                @csrf
                                <select name="assigned_to_id" required
                                        class="px-3 py-2 text-[13px] bg-white border border-[#E4E4E7] 
                                               rounded-lg outline-none focus:border-[#C8102E]">
                                    <option value="">Pilih Teknisi</option>
                                    @foreach(\App\Models\User::role('IT Support')->where('is_active', true)->get() as $it)
                                        <option value="{{ $it->id }}" @selected($ticket->assigned_to_id == $it->id)>
                                            {{ $it->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-ui.button type="submit" variant="secondary" size="sm">Assign</x-ui.button>
                            </form>
                        @endif

                        @if(auth()->user()->hasRole('Super Admin') || 
                            (auth()->user()->hasRole('IT Support') && $ticket->assigned_to_id === auth()->id()))
                            <button type="button" onclick="toggleModal('modalStatus')"
                                    class="app-btn app-btn-primary" style="padding: .5rem .875rem; font-size: 13px;">
                                Ubah Status
                            </button>
                        @endif

                        @if(in_array($ticket->status, ['resolved', 'closed']) 
                            && $ticket->requester_id === auth()->id())
                            <button type="button" onclick="toggleModal('modalReopen')"
                                    class="app-btn app-btn-secondary" style="padding: .5rem .875rem; font-size: 13px;">
                                Reopen
                            </button>
                        @endif

                        @if($ticket->status === 'resolved' && auth()->user()->hasAnyRole(['Super Admin', 'IT Support']))
                            <x-ui.button href="{{ route('kb.create', ['ticket_id' => $ticket->id]) }}"
                                         variant="secondary" size="sm">
                                Buat Artikel KB
                            </x-ui.button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Comments --}}
            <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                        shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
                    Diskusi ({{ $comments->count() }})
                </h2>

                <div class="space-y-4 mb-6">
                    @forelse($comments as $comment)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#F4F4F5] flex items-center 
                                        justify-center text-[12px] font-semibold text-[#52525B] flex-shrink-0">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <span class="text-[13px] font-semibold text-[#18181B]">
                                        {{ $comment->user->name }}
                                    </span>
                                    @if($comment->is_internal_note)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 
                                                     bg-[#FFF7ED] text-[#9A3412] 
                                                     rounded text-[11px] font-medium">
                                            🔒 Internal
                                        </span>
                                    @endif
                                    <span class="text-[11px] text-[#A1A1AA]">
                                        {{ $comment->created_at->format('d M Y · H:i') }}
                                    </span>
                                </div>
                                <p class="text-[14px] text-[#52525B] whitespace-pre-line leading-relaxed">
                                    {{ $comment->body }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-[13px] text-[#71717A] italic text-center py-4">
                            Belum ada diskusi.
                        </p>
                    @endforelse
                </div>

                <form action="{{ route('tickets.comment', $ticket) }}" method="POST"
                      class="border-t border-[#F0F0F1] pt-4">
                    @csrf
                    <textarea name="body" rows="3" required
                              placeholder="Tulis balasan..."
                              class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                     border border-[#E4E4E7] rounded-xl outline-none 
                                     focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF] 
                                     transition resize-none"></textarea>

                    <div class="flex items-center justify-between mt-3 gap-3 flex-wrap">
                        @if(auth()->user()->hasAnyRole(['Super Admin', 'IT Support']))
                            <label class="flex items-center gap-2 text-[13px] text-[#52525B] cursor-pointer">
                                <input type="checkbox" name="is_internal_note" value="1"
                                       class="rounded border-[#E4E4E7] text-[#C8102E] 
                                              focus:ring-[#FDECEF]">
                                Internal note
                            </label>
                        @else
                            <span></span>
                        @endif
                        <x-ui.button type="submit" size="sm">Kirim Balasan</x-ui.button>
                    </div>
                </form>
            </div>

            {{-- Timeline --}}
            <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5 lg:p-6
                        shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h2 class="text-[15px] font-semibold text-[#18181B] mb-5">
                    Aktivitas
                </h2>
                <div>
                    @forelse($ticket->activities as $act)
                        <x-tickets.activity-item :activity="$act" :last="$loop->last" />
                    @empty
                        <p class="text-[13px] text-[#71717A] italic">Belum ada aktivitas.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white border border-[#E4E4E7] rounded-2xl p-5
                        shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <h2 class="text-[15px] font-semibold text-[#18181B] mb-4">
                    Informasi Tiket
                </h2>

                <dl class="space-y-3 text-[13px]">
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Requester
                        </dt>
                        <dd class="text-[#18181B] font-medium">{{ $ticket->requester->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Ditangani
                        </dt>
                        <dd class="text-[#18181B]">
                            {{ $ticket->assignee?->name ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Kategori
                        </dt>
                        <dd class="text-[#18181B]">{{ $ticket->category?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Cabang
                        </dt>
                        <dd class="text-[#18181B]">{{ $ticket->branch?->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Dibuat
                        </dt>
                        <dd class="text-[#18181B]">{{ $ticket->created_at->format('d M Y · H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-semibold text-[#71717A] uppercase tracking-wider mb-0.5">
                            Deadline SLA
                        </dt>
                        <dd class="text-[#18181B]">
                            {{ $ticket->sla_resolution_deadline?->format('d M Y · H:i') ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

{{-- Modal Status --}}
<div id="modalStatus" 
     class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this) toggleModal('modalStatus')">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="text-[17px] font-semibold text-[#18181B] mb-1">Ubah Status</h3>
        <p class="text-[13px] text-[#71717A] mb-5">Pilih status baru untuk tiket ini.</p>

        <form action="{{ route('tickets.status', $ticket) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <x-ui.select 
                    name="status"
                    label="Status Baru"
                    required
                    :options="[
                        'in_progress' => 'In Progress',
                        'pending' => 'Pending',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ]"
                    placeholder="" />

                <div>
                    <label class="block text-[13px] font-medium text-[#18181B] mb-1.5">
                        Catatan (wajib saat Resolved)
                    </label>
                    <textarea name="note" rows="3"
                              class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                                     border border-[#E4E4E7] rounded-xl outline-none 
                                     focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF]"></textarea>
                </div>
            </div>

            <div class="flex gap-2 justify-end mt-6">
                <x-ui.button type="button" variant="secondary" 
                             onclick="toggleModal('modalStatus')">Batal</x-ui.button>
                <x-ui.button type="submit">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Reopen --}}
<div id="modalReopen" 
     class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center p-4"
     onclick="if(event.target===this) toggleModal('modalReopen')">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="text-[17px] font-semibold text-[#18181B] mb-1">Reopen Tiket</h3>
        <p class="text-[13px] text-[#71717A] mb-5">
            Jelaskan alasan mengapa tiket ini perlu dibuka kembali.
        </p>

        <form action="{{ route('tickets.reopen', $ticket) }}" method="POST">
            @csrf
            <textarea name="reason" rows="3" required
                      placeholder="Contoh: Masalah muncul kembali setelah 1 hari..."
                      class="w-full px-3.5 py-2.5 text-[14px] bg-white 
                             border border-[#E4E4E7] rounded-xl outline-none 
                             focus:border-[#C8102E] focus:ring-2 focus:ring-[#FDECEF]"></textarea>

            <div class="flex gap-2 justify-end mt-6">
                <x-ui.button type="button" variant="secondary" 
                             onclick="toggleModal('modalReopen')">Batal</x-ui.button>
                <x-ui.button type="submit">Reopen</x-ui.button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
    modal.classList.toggle('flex');
}
</script>
@endsection