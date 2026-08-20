<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="utf-8">
    <title>Event Entry Pass - {{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }
        .container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background: #0f172a;
            color: #ffffff;
            padding: 24px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #94a3b8;
        }
        .content {
            padding: 24px 20px;
        }
        .intro-text {
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            color: #334155;
        }
        .passes-list {
            margin: 20px 0;
        }
        /* Pass Card Inspired by Sketch */
        .pass-card {
            border: 2px solid #0f172a;
            border-radius: 12px;
            background: #ffffff;
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
        }
        .pass-top-strip {
            background: #0f172a;
            color: #ffffff;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pass-main {
            padding: 14px;
            display: table;
            width: 100%;
            box-sizing: border-box;
        }
        .pass-left-logo {
            display: table-cell;
            width: 85px;
            vertical-align: middle;
            text-align: center;
            padding-right: 12px;
        }
        .pass-logo-circle {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 2px solid #cbd5e1;
            background: #f8fafc;
            display: inline-block;
            overflow: hidden;
            line-height: 72px;
            text-align: center;
        }
        .pass-logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .pass-middle-content {
            display: table-cell;
            vertical-align: top;
            padding-right: 10px;
        }
        .pass-mandal-title {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }
        .pass-event-title {
            font-size: 15px;
            font-weight: 900;
            color: #e11d48;
            margin: 0 0 6px 0;
        }
        .pass-meta {
            font-size: 12px;
            color: #334155;
            margin: 3px 0;
            font-weight: 600;
        }
        .pass-right-box {
            display: table-cell;
            width: 110px;
            vertical-align: bottom;
            text-align: right;
        }
        .pass-no-box {
            border: 2px solid #0f172a;
            border-radius: 8px;
            background: #f8fafc;
            padding: 6px 8px;
            text-align: center;
        }
        .pass-no-label {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            display: block;
        }
        .pass-no-value {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
            display: block;
            margin-top: 2px;
        }
        .pass-footer-location {
            border-top: 1.5px dashed #cbd5e1;
            padding: 8px 14px;
            background: #f8fafc;
            font-size: 11px;
            font-weight: 700;
            color: #334155;
        }
        .footer-note {
            text-align: center;
            font-size: 12px;
            color: #64748b;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    @php
        $attendeeName = $registration->form_data['full_name'] ?? ($user ? $user->name : 'Member');
        $memberId = $user ? sprintf('#%05d', $user->id) : ($registration->form_data['member_id'] ?? '-');
        $logoUrl = App\Models\Setting::get('website_logo') ? asset('storage/' . App\Models\Setting::get('website_logo')) : asset('logo.png');
    @endphp

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎟️ ઇવેન્ટ પ્રવેશ પાસ / Event Entry Pass</h1>
            <p>સમસ્ત સાથવારા જ્ઞાતિ મંડળ - અમદાવાદ</p>
        </div>

        <div class="content">
            <p class="intro-text">
                નમસ્તે <strong>{{ $attendeeName }}</strong>,<br>
                તમે <strong>{{ $event->title }}</strong> કાર્યક્રમ માટે સફળતાપૂર્વક <strong>{{ $personCount }} પ્રવેશ પાસ</strong> બુક કર્યા છે. કાર્યક્રમ સ્થળે પ્રવેશ માટે નીચે આપેલ પાસ દર્શાવવાનો રહેશે.
            </p>

            <!-- Passes List Rendering (1 card per purchased pass) -->
            <div class="passes-list">
                @foreach($passes as $index => $passNo)
                    <div class="pass-card">
                        <!-- Top Strip -->
                        <div class="pass-top-strip">
                            <span> ENTRY PASS</span>
                            <!-- <span>ENTRY PASS</span> -->
                        </div>

                        <!-- Main Pass Body -->
                        <div class="pass-main">
                            <!-- Left: Circular Logo -->
                            <div class="pass-left-logo">
                                <div class="pass-logo-circle">
                                    <img src="{{ $logoUrl }}" alt="Logo" onerror="this.style.display='none'">
                                </div>
                            </div>

                            <!-- Middle: Mandal Name, Event Name, Date -->
                            <div class="pass-middle-content">
                                <div class="pass-mandal-title">
                                    Satwara Gyati Mandal Ahm.
                                </div>
                                <div class="pass-event-title">
                                    {{ $event->title }}
                                </div>
                                <div class="pass-meta">
                                    📅 <strong>Date:</strong> {{ date('d-M-Y', strtotime($event->date)) }} 
                                    @if($event->time) | ⏰ {{ date('h:i A', strtotime($event->time)) }} @endif
                                </div>

                            </div>

                            <!-- Right Box: Dedicated Pass No. -->
                            <div class="pass-right-box">
                                <div class="pass-no-box">
                                    <span class="pass-no-label">Pass No.</span>
                                    <span class="pass-no-value" style="font-size:20px; letter-spacing:3px;">{{ $passNo }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Location Strip -->
                        <div class="pass-footer-location">
                            📍 <strong>Location / Venue:</strong> {{ $event->venue }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="footer-note">
                <p style="margin: 0 0 5px 0;">આ પાસ તમારા મોબાઇલમાં સાચવી રાખો અથવા પ્રિન્ટ કરીને સાથે લાવો.</p>
                <p style="margin: 0; font-size: 11px; color: #94a3b8;">&copy; {{ date('Y') }} Samast Sathwara Gyati Mandal Ahmedabad. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
