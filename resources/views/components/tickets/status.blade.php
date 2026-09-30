@props(['status'])

@php
    $map = [
        'new' => ['label' => 'Open', 'variant' => 'info'],
        'assigned' => ['label' => 'Assigned', 'variant' => 'info'],
        'in_progress' => ['label' => 'In Progress', 'variant' => 'warning'],
        'pending' => ['label' => 'Pending', 'variant' => 'warning'],
        'resolved' => ['label' => 'Resolved', 'variant' => 'success'],
        'closed' => ['label' => 'Closed', 'variant' => 'neutral'],
        'reopened' => ['label' => 'Reopened', 'variant' => 'danger'],
        'cancelled' => ['label' => 'Cancelled', 'variant' => 'neutral'],
    ];
    $data = $map[$status] ?? ['label' => ucfirst($status), 'variant' => 'neutral'];
@endphp

<x-ui.badge :variant="$data['variant']" dot>
    {{ $data['label'] }}
</x-ui.badge>