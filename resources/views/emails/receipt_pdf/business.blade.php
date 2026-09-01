@extends('emails.receipt_pdf._base')

@section('type_label', 'Business Registration Receipt')

@section('content')
    <!-- Business Details -->
    <div class="section-header">Business Listing Details</div>
    <table class="info-table">
        <tr>
            <td class="label">Business Name:</td>
            <td class="value accent">{{ $business->business_name }}</td>
        </tr>
        <tr>
            <td class="label">Category:</td>
            <td class="value">{{ $business->category ? $business->category->name : 'General Business' }}</td>
        </tr>
        <tr>
            <td class="label">Owner / Contact Person:</td>
            <td class="value">{{ $business->owner_name }}</td>
        </tr>
        @if(!empty($business->member_id))
        <tr>
            <td class="label">Member ID Reference:</td>
            <td class="value">{{ $business->member_id }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Phone / WhatsApp:</td>
            <td class="value">{{ $business->phone }} {{ !empty($business->whatsapp) ? '/ ' . $business->whatsapp : '' }}</td>
        </tr>
        @if(!empty($business->email))
        <tr>
            <td class="label">Business Email:</td>
            <td class="value">{{ $business->email }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Address / Area:</td>
            <td class="value">{{ $business->address }} {{ $business->area ? '(' . $business->area->name . ')' : '' }}</td>
        </tr>
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
                    <strong>Annual Business Directory Listing Fee</strong>
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
