@php
$config = match($status) {
    'pending'   => ['badge-pending',   '⏳', 'Pending'],
    'paid'      => ['badge-paid',      '💳', 'Paid'],
    'completed' => ['badge-completed', '✅', 'Completed'],
    'cancelled' => ['badge-cancelled', '✕',  'Cancelled'],
    default     => ['badge-cancelled',  '',  ucfirst($status)],
};
@endphp
<span class="{{ $config[0] }}">
    {{ $config[1] }} {{ $config[2] }}
</span>
