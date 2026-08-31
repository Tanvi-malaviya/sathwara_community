<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Membership Receipt - {{ $receiptNo }}</title>
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
            border: 2px solid #1e3a8a;
            border-radius: 8px;
            padding: 20px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .org-title {
            font-size: 17px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .org-subtitle {
            font-size: 11px;
            color: #64748b;
            margin-top: 3px;
        }
        .receipt-title-badge {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            padding: 6px 12px;
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
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 11px;
        }
        .section-header {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 15px;
            margin-bottom: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 5px 8px;
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
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .payment-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            padding: 6px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }
        .payment-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        .total-row td {
            background-color: #f1f5f9;
            font-size: 13px;
            font-weight: bold;
            border-top: 2px solid #cbd5e1;
        }
        .status-badge {
            background-color: #dcfce7;
            color: #15803d;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            font-size: 10px;
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
    @endphp

    <div class="receipt-box">
        <!-- Header -->
        <table class="header-table">
            <tr>
                @if($logoBase64)
                <td style="width: 70px; vertical-align: middle; padding-right: 10px;">
                    <img src="{{ $logoBase64 }}" style="max-height: 60px; max-width: 65px; object-fit: contain;" alt="Logo">
                </td>
                @endif
                <td style="vertical-align: middle;">
                    <div class="org-title">{{ config('app.name', 'Satwara Community Portal') }}</div>
                    <div class="org-subtitle">Samast Satwara Gyati Mandal • Official Membership Receipt</div>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 140px;">
                    <div class="receipt-title-badge">PAYMENT RECEIPT</div>
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

        <!-- Member Details -->
        <div class="section-header">Member Information</div>
        <table class="info-table">
            <tr>
                <td class="label">Member ID / Code:</td>
                <td class="value">{{ $user->member_code ?? ('SSAM' . sprintf('%04d', $user->id)) }}</td>
            </tr>
            <tr>
                <td class="label">Full Name:</td>
                <td class="value">{{ $user->name }}</td>
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
                <td class="value">{{ $profile->city }} ({{ $profile->state ?? 'Gujarat' }})</td>
            </tr>
            @endif
            @if(!empty($profile->address))
            <tr>
                <td class="label">Address:</td>
                <td class="value">{{ $profile->address }}</td>
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
                        <strong>Lifetime / Annual Membership Registration Fee</strong><br>
                        <span style="font-size: 10px; color: #64748b;">Community Member Registration and Digital ID Card</span>
                    </td>
                    <td style="text-align: center;">
                        {{ !empty($paymentId) ? 'Razorpay Online' : 'Offline / Cash' }}
                    </td>
                    <td style="text-align: right; font-weight: bold;">
                        Rs. {{ number_format($amount, 2) }}
                    </td>
                </tr>
                @if(!empty($paymentId))
                <tr>
                    <td colspan="3" style="font-size: 10px; color: #475569;">
                        <strong>Transaction ID:</strong> {{ $paymentId }} | 
                        <strong>Payment Status:</strong> <span class="status-badge">{{ strtoupper($paymentStatus) }}</span>
                    </td>
                </tr>
                @endif
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Paid Amount:</td>
                    <td style="text-align: right; color: #1e3a8a;">Rs. {{ number_format($amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
