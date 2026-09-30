@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<x-ui.page-header 
    title="Notifikasi"
    subtitle="Pemberitahuan aktivitas tiket dan sistem.">
    @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="app-btn app-btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Tandai Semua Dibaca
            </button>
        </form>
    @endif
</x-ui.page-header>

<div class="max-w-4xl mx-auto space-y-2">

    @forelse($notifications as $notif)
        <a href="{{ route('notifications.read', $notif->id) }}"
           class="block bg-white border rounded-2xl p-4 lg:p-5 transition
                  hover:border-[#C8102E] hover:shadow-[0_4px_12px_rgba(0,0,0,0.04)]
                  {{ !$notif->is_read 
                     ? 'border-l-4 border-l-[#C8102E] border-y-[#E4E4E7] border-r-[#E4E4E7] bg-[#FDECEF]/30' 
                     : 'border-[#E4E4E7]' }}">

            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0
                            {{ !$notif->is_read 
                               ? 'bg-[#FDECEF] text-[#C8102E]' 
                               : 'bg-[#F4F4F5] text-[#71717A]' }}">
                    @if($notif->type === 'ticket_created')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    @elseif($notif->type === 'ticket_status_changed')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    @elseif($notif->type === 'sla_warning' || $notif->type === 'sla_breach')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    @elseif($notif->type === 'ticket_commented')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" 
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 mb-1">
                        <p class="text-[14px] font-semibold text-[#18181B] {{ !$notif->is_read ? '' : 'opacity-80' }}">
                            {{ $notif->title }}
                        </p>
                        <span class="text-[11px] text-[#A1A1AA] flex-shrink-0">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-[13px] text-[#52525B] leading-relaxed {{ !$notif->is_read ? '' : 'opacity-80' }}">
                        {{ $notif->message }}
                    </p>
                    @if(!$notif->is_read)
                        <span class="inline-flex items-center gap-1 mt-2 
                                     text-[11px] font-medium text-[#C8102E]">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#C8102E]"></span>
                            Belum dibaca
                        </span>
                    @endif
                </div>

                <svg class="w-4 h-4 text-[#A1A1AA] flex-shrink-0 mt-2" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    @empty
        <div class="bg-white border border-[#E4E4E7] rounded-2xl shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
            <x-ui.empty-state 
                title="Tidak ada notifikasi"
                description="Semua pemberitahuan akan muncul di sini." />
        </div>
    @endforelse

    @if($notifications->hasPages())
        <div class="pt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection