<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Event Pass & Payment Receipt - {{ $receiptNo }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.4;
        }
        .receipt-box {
            border: 2px solid #0f172a;
            border-radius: 8px;
            padding: 18px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }
        .org-title {
            font-size: 17px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
        }
        .org-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .receipt-title-badge {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            padding: 5px 10px;
            border-radius: 4px;
            text-align: center;
            display: inline-block;
            letter-spacing: 0.5px;
        }
        .meta-table {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .section-header {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
            margin-top: 12px;
            margin-bottom: 6px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .info-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td.label {
            color: #64748b;
            font-weight: bold;
            width: 35%;
        }
        .info-table td.value {
            color: #0f172a;
            font-weight: bold;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 12px;
        }
        .payment-table th {
            background-color: #0f172a;
            color: #ffffff;
            padding: 5px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .payment-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .total-row td {
            background-color: #f1f5f9;
            font-size: 12px;
            font-weight: bold;
            border-top: 2px solid #cbd5e1;
        }
        /* Pass Tickets Box */
        .pass-container {
            border: 1.5px solid #e11d48;
            border-radius: 6px;
            background-color: #fff1f2;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 12px;
        }
        .pass-tag {
            display: inline-block;
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 4px;
            margin-right: 6px;
            margin-bottom: 6px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    @php
        $logoSetting = \App\Models\Setting::get('website_logo');
        $logoPath = $logoSetting && file_exists(storage_path('app/public/' . $logoSetting)) 
            ? storage_path('app/public/' . $logoSetting) 
            : (file_exists(public_path('logo.png')) ? public_path('logo.png') : null);
        $logoBase64 = $logoPath ? 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath)) : null;
        $attendeeName = $registration->form_data['full_name'] ?? ($user ? $user->name : 'Member');
        $finalAmount = $amount ?? (float)($registration->payment_amount ?? 0);
        $finalPaymentStatus = $paymentStatus ?? ($registration->payment_status ?? 'paid');
    @endphp

    <div class="receipt-box">
        <!-- Header -->
        <table class="header-table">
            <tr>
                @if($logoBase64)
                <td style="width: 65px; vertical-align: middle; padding-right: 10px;">
                    <img src="{{ $logoBase64 }}" style="max-height: 55px; max-width: 60px; object-fit: contain;" alt="Logo">
                </td>
                @endif
                <td style="vertical-align: middle;">
                    <div class="org-title">{{ config('app.name', 'Satwara Community Portal') }}</div>
                    <div class="org-subtitle">Samast Satwara Gyati Mandal • Event Entry Pass & Receipt</div>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 140px;">
                    <div class="receipt-title-badge">PASS & RECEIPT</div>
                </td>
            </tr>
        </table>

        <!-- Receipt Meta Details -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <strong>Receipt Number:</strong> {{ $receiptNo }}
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Date & Time:</strong> {{ date('d M Y, h:i A') }}
                </td>
            </tr>
        </table>

        <!-- Event Details -->
        <div class="section-header">Event Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Event Title:</td>
                <td class="value" style="color: #e11d48; font-size: 13px;">{{ $event->title }}</td>
            </tr>
            <tr>
                <td class="label">Event Date & Time:</td>
                <td class="value">
                    @php
                        $eventDate = $event->date ?? ($event->event_date ?? null);
                        $eventTime = $event->time ?? ($event->start_time ?? null);
                    @endphp
                    {{ $eventDate ? date('d M Y', strtotime($eventDate)) : '-' }}
                    @if($eventTime) | {{ date('h:i A', strtotime($eventTime)) }} @endif
                </td>
            </tr>
            <tr>
                <td class="label">Venue / Location:</td>
                <td class="value">{{ $event->venue ?? 'Community Hall' }}</td>
            </tr>
            <tr>
                <td class="label">Primary Attendee:</td>
                <td class="value">{{ $attendeeName }}</td>
            </tr>
        </table>

        <!-- Pass Tokens Box -->
        <div class="section-header">Verified Digital Passes</div>
        <div class="pass-container">
            <div style="font-size: 11px; font-weight: bold; color: #9f1239; margin-bottom: 6px;">
                Total Passes Issued: {{ $personCount }} Person(s)
            </div>
            <div>
                @foreach($passes as $index => $passNo)
                    <span class="pass-tag">PASS #{{ $passNo }}</span>
                @endforeach
            </div>
        </div>

        <!-- Payment Breakdown -->
        <div class="section-header">Payment Breakdown</div>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="width: 20%; text-align: center;">Quantity</th>
                    <th style="width: 25%; text-align: right;">Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Event Entry Pass Fee</strong><br>
                        <span style="font-size: 9px; color: #64748b;">{{ !empty($paymentId) ? 'Paid Online (Razorpay: ' . $paymentId . ')' : 'Paid Offline / Cash' }}</span>
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ $personCount }} Person(s)
                    </td>
                    <td style="text-align: right; font-weight: bold;">
                        Rs. {{ number_format($finalAmount, 2) }}
                    </td>
                </tr>
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Paid Amount:</td>
                    <td style="text-align: right; color: #e11d48;">Rs. {{ number_format($finalAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
