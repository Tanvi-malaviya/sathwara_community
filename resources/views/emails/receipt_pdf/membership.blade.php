@extends('emails.receipt_pdf._base')

@section('type_label', 'Membership Receipt')

@section('content')
    <!-- Member Details -->
    <div class="section-header">Member Information</div>
    <table class="info-table">
        <tr>
            <td class="label">Member Code:</td>
            <td class="value">{{ $user->member_code ?? ('SSAM' . sprintf('%04d', $user->id)) }}</td>
        </tr>
        <tr>
            <td class="label">Full Name:</td>
            <td class="value">{{ \App\Support\GujaratiText::reorderMatra($user->name) }}</td>
        </tr>
        <tr>
            <td class="label">Phone / Mobile:</td>
            <td class="value">{{ $profile->phone ?? ($user->phone ?? '-') }}</td>
        </tr>
        <tr>
            <td class="label">Email Address:</td>
            <td class="value">{{ $user->email }}</td>
        </tr>
        @if(!empty($profile->city))
        <tr>
            <td class="label">City / Village:</td>
            <td class="value">{{ \App\Support\GujaratiText::reorderMatra($profile->city) }} ({{ \App\Support\GujaratiText::reorderMatra($profile->state ?? 'Gujarat') }})</td>
        </tr>
        @endif
        @if(!empty($profile->address))
        <tr>
            <td class="label">Address:</td>
            <td class="value">{{ \App\Support\GujaratiText::reorderMatra($profile->address) }}</td>
        </tr>
        @endif
    </table>

    <!-- Payment Breakdown -->
    <div class="section-header">Payment Breakdown</div>
    <table class="payment-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="width: 25%; text-align: center;">Payment Mode</th>
                <th style="width: 25%; text-align: right;">Amount (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>Lifetime Membership Registration Fee</strong>
                </td>
                <td style="text-align: center;">
                    {{ !empty($paymentId) ? 'Razorpay Online' : 'Offline / Cash' }}
                </td>
                <td style="text-align: right; font-weight: bold;">
                    Rs. {{ number_format($amount, 2) }}
                </td>
            </tr>
            @include('emails.receipt_pdf._txn_status')
            <tr class="total-row">
                <td colspan="2" style="text-align: right;">Total Paid Amount:</td>
                <td style="text-align: right; color: #1e3a8a;">Rs. {{ number_format($amount, 2) }}</td>
            </tr>
        </tbody>
    </table>
@endsection
