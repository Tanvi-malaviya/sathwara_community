<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>@yield('type_label') - {{ $receiptNo }}</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'HindVadodara', 'Helvetica Neue', Helvetica, Arial, sans-serif;
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
            margin-bottom: 8px;
        }
        .org-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .type-badge-wrap {
            text-align: center;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .type-badge {
            background-color: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            font-size: 12px;
            padding: 7px 18px;
            border-radius: 20px;
            display: inline-block;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-align: center;
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
        .info-table td.value.accent {
            color: #1e3a8a;
            font-size: 13px;
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
        .txn-row td {
            font-size: 10px;
            color: #475569;
            padding: 8px 10px;
        }
        .status-badge {
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            display: inline-block;
            font-size: 10px;
        }
        .status-badge.status-good {
            background-color: #dcfce7;
            color: #15803d;
        }
        .status-badge.status-pending {
            background-color: #fef9c3;
            color: #92400e;
        }
        .status-badge.status-bad {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .footer-note {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #64748b;
            text-align: center;
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
        $orgName = \App\Models\Setting::get('website_name', 'Shree Satwara Gnati Mandal, Ahmedabad');
    @endphp

    <div class="receipt-box">
        @php
            $orgAddress = \App\Models\Setting::get('contact_address', '1, Satwara Samaj Bhavan, Opp. Siddheswar Shopping, Viratnagar-Manmohan Road, Odhav, Ahmedabad - 382415');
            $orgPhone = \App\Models\Setting::get('contact_phone', '+91-6353785519');
        @endphp

        <!-- Header -->
        <table class="header-table" style="width: 100%; border-collapse: collapse;">
            <tr>
                @if($logoBase64)
                <td style="width: 65px; vertical-align: middle; text-align: left;">
                    <img src="{{ $logoBase64 }}" style="max-height: 55px; max-width: 60px; object-fit: contain;" alt="Logo">
                </td>
                @endif
                <td style="text-align: center; vertical-align: middle;">
                    <div class="org-title">{{ \App\Support\GujaratiText::reorderMatra($orgName) }}</div>
                    @if(!empty($orgAddress))
                        <div style="font-size: 9.5px; color: #334155; font-weight: bold; margin-top: 3px; line-height: 1.3;">
                            {{ \App\Support\GujaratiText::reorderMatra($orgAddress) }}
                        </div>
                    @endif
                    @if(!empty($orgPhone))
                        <div style="font-size: 10px; color: #1e3a8a; font-weight: bold; margin-top: 2px;">
                            Mo. / Mobile: {{ $orgPhone }}
                        </div>
                    @endif
                </td>
                @if($logoBase64)
                <td style="width: 65px;"></td>
                @endif
            </tr>
        </table>

        <!-- Centered Title Badge -->
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px;">
            <tr>
                <td align="center" style="text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px;">
                    <span class="type-badge">@yield('type_label')</span>
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
                    <strong>Date &amp; Time:</strong> {{ date('d M Y, h:i A') }}
                </td>
            </tr>
        </table>

        @yield('content')
    </div>
</body>
</html>
