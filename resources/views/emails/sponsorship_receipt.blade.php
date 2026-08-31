<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="utf-8">
    <title>Sponsorship Receipt - {{ $receiptNo }}</title>
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
            background: linear-gradient(135deg, #7c2d12 0%, #0f172a 100%);
            color: #ffffff;
            padding: 28px 24px;
            text-align: center;
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
            color: #fdba74;
        }
        .receipt-badge {
            display: inline-block;
            background: #ea580c;
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
            background: #fffaf5;
            border: 1.5px dashed #fed7aa;
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
            border-top: 1px solid #fed7aa;
            margin-top: 8px;
        }
        .amount-row .amount {
            color: #c2410c;
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
        .status-received {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .status-pending {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .appreciation-card {
            background: linear-gradient(to right, #fff7ed, #ffedd5);
            border-left: 4px solid #ea580c;
            padding: 14px 16px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 20px;
        }
        .appreciation-card h4 {
            margin: 0 0 6px 0;
            color: #9a3412;
            font-size: 13px;
            font-weight: 800;
        }
        .appreciation-card p {
            margin: 0;
            font-size: 12px;
            color: #7c2d12;
            line-height: 1.6;
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
            color: #ea580c;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'Satwara Community Portal') }}</h1>
            <p>ઇવેન્ટ સ્પોન્સરશિપ સહયોગ સત્તાવાર રસીદ</p>
            <div class="receipt-badge">Sponsorship Contribution Receipt</div>
        </div>

        <!-- Content -->
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

            <!-- Heartfelt Appreciation Card -->
            <div class="appreciation-card">
                <h4>🙏 હૃદયપૂર્વક આભાર (Heartfelt Gratitude)</h4>
                <p>
                    શ્રીમાન <strong>{{ $sponsor->name }}</strong>, સમસ્ત સતવારા સમાજના કાર્યક્રમ <strong>"{{ $event->title }}"</strong> માં આપના ઉમદા સ્પોન્સરશિપ સહયોગ બદલ અમે આપનો અંતઃકરણપૂર્વક આભાર માનીએ છીએ.
                </p>
            </div>

            <!-- Event Details -->
            <div class="section-title">Event & Sponsorship Details (ઇવેન્ટ વિગતો)</div>
            <table class="data-table">
                <tr>
                    <td class="label">Event Name</td>
                    <td class="value"><strong style="color: #1e3a8a;">{{ $event->title }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Event Date & Time</td>
                    <td class="value">
                        @php
                            $eventDate = $event->date ?? ($event->event_date ?? null);
                            $eventTime = $event->time ?? ($event->start_time ?? null);
                        @endphp
                        {{ $eventDate ? \Carbon\Carbon::parse($eventDate)->format('d M Y') : '-' }}
                        @if($eventTime)
                            | {{ \Carbon\Carbon::parse($eventTime)->format('h:i A') }}
                        @endif
                    </td>
                </tr>
                @if(!empty($event->venue))
                <tr>
                    <td class="label">Venue</td>
                    <td class="value">{{ $event->venue }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Sponsorship Package</td>
                    <td class="value">
                        <span style="background: #ffedd5; color: #9a3412; padding: 3px 8px; border-radius: 4px; font-weight: 800;">
                            🏷️ {{ $sponsorshipType ? $sponsorshipType->title : 'General Sponsorship' }}
                        </span>
                    </td>
                </tr>
            </table>

            <!-- Sponsor Details -->
            <div class="section-title">Sponsor Organization Details (સ્પોન્સર વિગતો)</div>
            <table class="data-table">
                <tr>
                    <td class="label">Sponsor / Company Name</td>
                    <td class="value"><strong>{{ $sponsor->name }}</strong></td>
                </tr>
                @if(!empty($sponsor->contact_person))
                <tr>
                    <td class="label">Contact Person</td>
                    <td class="value">{{ $sponsor->contact_person }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Phone / Mobile</td>
                    <td class="value">{{ $sponsor->mobile }}</td>
                </tr>
                @if(!empty($sponsor->email))
                <tr>
                    <td class="label">Email Address</td>
                    <td class="value">{{ $sponsor->email }}</td>
                </tr>
                @endif
                @if(!empty($sponsor->city))
                <tr>
                    <td class="label">City / Location</td>
                    <td class="value">{{ $sponsor->city }}</td>
                </tr>
                @endif
            </table>

            <!-- Payment Summary -->
            <div class="section-title">Contribution Summary (રકમ સારાંશ)</div>
            <div class="payment-box">
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Contribution Type</td>
                        <td style="font-weight: 700; text-align: right; color: #0f172a;">Event Sponsorship Contribution</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Payment Method</td>
                        <td style="font-weight: 700; text-align: right; color: #0f172a;">
                            {{ !empty($paymentId) ? 'Online (Razorpay)' : 'Offline / Cheque / Cash' }}
                        </td>
                    </tr>
                    @if(!empty($paymentId))
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Transaction / Payment ID</td>
                        <td style="font-weight: 700; text-align: right; font-family: monospace; color: #c2410c;">{{ $paymentId }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Payment Status</td>
                        <td style="text-align: right;">
                            <span class="status-pill {{ $paymentStatus === 'received' ? 'status-received' : 'status-pending' }}">
                                {{ strtoupper($paymentStatus) }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="amount-row">
                    <span>Sponsorship Amount:</span>
                    <span class="amount">₹ {{ number_format($amount, 2) }}</span>
                </div>
            </div>

            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                આ એક સત્તાવાર ડિજિટલ રસીદ છે. ભવિષ્યના સંદર્ભ માટે આ રસીદ સાચવી રાખો.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px 0;">© {{ date('Y') }} {{ config('app.name', 'Satwara Community') }}. All rights reserved.</p>
            <p style="margin: 0;">Event Page: <a href="{{ route('event.details', $event->id) }}">{{ route('event.details', $event->id) }}</a></p>
        </div>
    </div>
</body>
</html>
