<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="utf-8">
    <title>Membership Purchase Receipt - {{ $receiptNo }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            color: #0f172a;
        }
        .container {
            max-width: 620px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .receipt-badge {
            display: inline-block;
            background: #10b981;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            padding: 4px 14px;
            border-radius: 20px;
            margin-top: 12px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .content {
            padding: 24px;
        }
        .meta-strip {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }
        .meta-item strong {
            color: #334155;
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .section-title {
            font-size: 13px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 6px;
            margin-top: 20px;
            margin-bottom: 14px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .data-table tr td {
            padding: 8px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tr td.label {
            color: #64748b;
            font-weight: 600;
            width: 38%;
        }
        .data-table tr td.value {
            color: #0f172a;
            font-weight: 700;
        }
        .payment-box {
            background: #f8fafc;
            border: 1.5px dashed #cbd5e1;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 22px;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
            font-weight: 900;
            color: #0f172a;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            margin-top: 8px;
        }
        .amount-row .amount {
            color: #059669;
            font-size: 20px;
        }
        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .status-paid {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .status-unpaid {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .info-alert {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 14px;
            border-radius: 0 8px 8px 0;
            font-size: 12px;
            color: #1e40af;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 18px 24px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'Shree Satwara Gnati Mandal, Ahmedabad') }}</h1>
            <p>સભ્યપદ ખરીદી / નોંધણી સત્તાવાર રસીદ</p>
            <div class="receipt-badge">Official Payment Receipt</div>
        </div>

        <!-- Body Content -->
        <div class="content">
            <!-- Receipt Meta Info -->
            <table style="width: 100%; background: #f1f5f9; border-radius: 10px; padding: 10px 14px; margin-bottom: 20px; font-size: 12px;">
                <tr>
                    <td style="width: 50%;">
                        <span style="font-size: 10px; color: #64748b; font-weight: 800; text-transform: uppercase; display: block;">Receipt No</span>
                        <strong style="color: #0f172a; font-size: 13px;">{{ $receiptNo }}</strong>
                    </td>
                    <td style="width: 50%; text-align: right;">
                        <span style="font-size: 10px; color: #64748b; font-weight: 800; text-transform: uppercase; display: block;">Date & Time</span>
                        <strong style="color: #0f172a; font-size: 12px;">{{ date('d M Y, h:i A') }}</strong>
                    </td>
                </tr>
            </table>

            <!-- Member Info -->
            <div class="section-title">Member Details (સભ્ય વિગતો)</div>
            <table class="data-table">
                <tr>
                    <td class="label">Member ID / Code</td>
                    <td class="value">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-weight: 800;">
                            {{ $user->member_code ?? ('SSAM' . sprintf('%04d', $user->id)) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Full Name</td>
                    <td class="value">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Phone / Mobile</td>
                    <td class="value">{{ $profile->phone ?? ($user->phone ?? '-') }}</td>
                </tr>
                <tr>
                    <td class="label">Registered Email</td>
                    <td class="value">{{ $user->email }}</td>
                </tr>
                @if(!empty($profile->city))
                <tr>
                    <td class="label">City / Village</td>
                    <td class="value">{{ $profile->city }} ({{ $profile->state ?? 'Gujarat' }})</td>
                </tr>
                @endif
                @if(!empty($profile->address))
                <tr>
                    <td class="label">Address</td>
                    <td class="value">{{ $profile->address }}</td>
                </tr>
                @endif
            </table>

            <!-- Payment Breakdown -->
            <div class="section-title">Payment Summary (ચુકવણી સારાંશ)</div>
            <div class="payment-box">
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Description</td>
                        <td style="font-weight: 700; text-align: right; color: #0f172a;">Community Lifetime / Annual Membership Registration</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Payment Method</td>
                        <td style="font-weight: 700; text-align: right; color: #0f172a;">
                            {{ !empty($paymentId) ? 'Online (Razorpay)' : 'Offline / Cash' }}
                        </td>
                    </tr>
                    @if(!empty($paymentId))
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Transaction / Payment ID</td>
                        <td style="font-weight: 700; text-align: right; font-family: monospace; color: #2563eb;">{{ $paymentId }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Payment Status</td>
                        <td style="text-align: right;">
                            <span class="status-pill {{ $paymentStatus === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                                {{ strtoupper($paymentStatus) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="amount-row">
                    <span>Total Amount Paid:</span>
                    <span class="amount">₹ {{ number_format($amount, 2) }}</span>
                </div>
            </div>

            <!-- Important Information -->
            <div class="info-alert">
                <strong>📌 Important Notice:</strong><br>
                તમારી સભ્યપદ નોંધણી સ્વીકારાઈ ગઈ છે. એડમિન દ્વારા વેરિફિકેશન પૂર્ણ થયા બાદ તમારું એકાઉન્ટ સક્રિય થશે અને તમે તમારા મેમ્બર પોર્ટલ પરથી ડિજિટલ સભ્યપદ કાર્ડ ડાઉનલોડ કરી શકશો.
            </div>

            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                આ એક કોમ્પ્યુટર જનરેટેડ ઈલેક્ટ્રોનિક રસીદ છે, જેથી કોઈ સહીની જરૂર નથી. ભવિષ્યના સંદર્ભ માટે આ રસીદ સાચવી રાખો.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px 0;">© {{ date('Y') }} {{ config('app.name', 'Shree Satwara Gnati Mandal, Ahmedabad') }}. All rights reserved.</p>
            <p style="margin: 0;">Need Help? Contact Community Helpline or visit <a href="{{ url('/') }}">{{ url('/') }}</a></p>
        </div>
    </div>
</body>
</html>
