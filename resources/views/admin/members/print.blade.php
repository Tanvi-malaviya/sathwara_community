<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Community Members Print List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 2px;
        }
        h4 {
            text-align: center;
            margin-top: 2px;
            color: #777;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
    </style>
</head>
<body onload="window.print()">
 
    <h2>Satwara Community Social Portal</h2>
    <h4>Member Registration Directory — Generated: {{ date('d-M-Y h:i A') }}</h4>
 
    <table>
        <thead>
            <tr>
                <th>{{ __('messages.member_id') }}</th>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.email') }}</th>
                <th>{{ __('messages.phone') }}</th>
                <th>{{ __('messages.city') }}</th>
                <th>{{ __('messages.registration_date') }}</th>
                <th>{{ __('messages.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($members as $m)
                <tr>
                    <td>{{ $m->member_code ?: $m->formatted_member_id }}</td>
                    <td><strong>{{ $m->name }}</strong></td>
                    <td>{{ $m->email ?: '-' }}</td>
                    <td>{{ $m->memberProfile->phone ?? 'N/A' }}</td>
                    <td>{{ $m->memberProfile->city ?? 'N/A' }}</td>
                    <td>{{ $m->created_at->format('d-M-Y') }}</td>
                    <td><span class="badge">{{ $m->status }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
