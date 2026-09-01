@php
    $statusKey = strtolower($paymentStatus ?? 'paid');
    $statusClass = in_array($statusKey, ['paid', 'received', 'success', 'completed'])
        ? 'status-good'
        : (in_array($statusKey, ['pending', 'unpaid']) ? 'status-pending' : 'status-bad');
@endphp
@if(!empty($paymentId))
<tr>
    <td colspan="3" class="txn-row">
        <strong>Transaction ID:</strong> {{ $paymentId }}
        &nbsp;|&nbsp;
        <strong>Payment Status:</strong>
        <span class="status-badge {{ $statusClass }}">{{ strtoupper($paymentStatus ?? 'paid') }}</span>
    </td>
</tr>
@endif
