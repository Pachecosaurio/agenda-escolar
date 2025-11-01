@props(['status'])
@php
    $map = [
        'paid' => ['Pagado','fa-check-circle','bg-success text-white'],
        'pending' => ['Pendiente','fa-clock','bg-warning text-dark'],
        'overdue' => ['Vencido','fa-exclamation-triangle','bg-danger text-white'],
    ];
    [$label,$icon,$classes] = $map[$status] ?? ['N/D','fa-question','bg-secondary text-white'];
@endphp
<span class="badge {{ $classes }}">
    <i class="fas {{ $icon }} me-1"></i>{{ $label }}
</span>