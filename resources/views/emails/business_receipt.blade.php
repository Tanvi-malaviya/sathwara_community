<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="utf-8">
    <title>Business Registration Receipt - {{ $receiptNo }}</title>
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
            background: linear-gradient(135deg, #065f46 0%, #0f172a 100%);
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
            background: #ecfdf5;
            border-left: 4px solid #10b981;
            padding: 12px 14px;
            border-radius: 0 8px 8px 0;
            font-size: 12px;
            color: #065f46;
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
            color: #059669;
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
            <p>બિઝનેસ ડિરેક્ટરી નોંધણી સત્તાવાર રસીદ</p>
            <div class="receipt-badge">Business Registration Receipt</div>
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

            <!-- Business Info -->
            <div class="section-title">Business Listing Details (વ્યવસાય વિગતો)</div>
            <table class="data-table">
                <tr>
                    <td class="label">Business Name</td>
                    <td class="value">
                        <strong style="font-size: 14px; color: #065f46;">{{ $business->business_name }}</strong>
                    </td>
                </tr>
                <tr>
                    <td class="label">Category</td>
                    <td class="value">{{ $business->category ? $business->category->name : 'General Business' }}</td>
                </tr>
                <tr>
                    <td class="label">Owner / Contact Person</td>
                    <td class="value">{{ $business->owner_name }}</td>
                </tr>
                @if(!empty($business->member_id))
                <tr>
                    <td class="label">Member ID Reference</td>
                    <td class="value">{{ $business->member_id }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Phone / WhatsApp</td>
                    <td class="value">{{ $business->phone }} {{ !empty($business->whatsapp) ? '/ ' . $business->whatsapp : '' }}</td>
                </tr>
                @if(!empty($business->email))
                <tr>
                    <td class="label">Business Email</td>
                    <td class="value">{{ $business->email }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Address / Location</td>
                    <td class="value">{{ $business->address }} {{ $business->area ? '(' . $business->area->name . ')' : '' }}</td>
                </tr>
            </table>

            <!-- Payment Summary -->
            <div class="section-title">Payment Summary (ચુકવણી સારાંશ)</div>
            <div class="payment-box">
                <table style="width: 100%; font-size: 13px;">
                    <tr>
                        <td style="color: #64748b; padding: 4px 0;">Description</td>
                        <td style="font-weight: 700; text-align: right; color: #0f172a;">Community Business Directory Listing Fee</td>
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
                        <td style="font-weight: 700; text-align: right; font-family: monospace; color: #059669;">{{ $paymentId }}</td>
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
                    <span>Total Amount:</span>
                    <span class="amount">₹ {{ number_format($amount, 2) }}</span>
                </div>
            </div>

            <!-- Notice -->
            <div class="info-alert">
                <strong>🏢 Listing Notice:</strong><br>
                તમારો વ્યવસાય સફળાતાપૂર્વક સબમિટ થયો છે. એડમિન દ્વારા ચકાસણી પૂર્ણ થયા પછી તમારો વ્યવસાય પબ્લિક બિઝનેસ ડિરેક્ટરીમાં લાઈવ જોવા મળશે.
            </div>

            <p style="font-size: 12px; color: #64748b; line-height: 1.5; margin: 0;">
                આ એક કોમ્પ્યુટર જનરેટેડ ઈલેક્ટ્રોનિક રસીદ છે. ભવિષ્યના સંદર્ભ માટે આ રસીદ સાચવી રાખો.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 6px 0;">© {{ date('Y') }} {{ config('app.name', 'Shree Satwara Gnati Mandal, Ahmedabad') }}. All rights reserved.</p>
            <p style="margin: 0;">View Directory at <a href="{{ route('business.directory') }}">{{ route('business.directory') }}</a></p>
        </div>
    </div>
</body>
</html>
