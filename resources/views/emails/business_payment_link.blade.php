<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Renewal Payment Link</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="570" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #0f172a; padding: 32px 40px; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 20px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;">Shree Satwara Gnati Mandal, Ahmedabad</h1>
                            <p style="margin: 6px 0 0 0; font-size: 11px; color: #94a3b8; font-weight: 600;">BUSINESS RENEWAL PAYMENT</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 800; color: #1e293b; text-align: center;">Renew Your Business Listing</h2>
                            <p style="margin: 0 0 24px 0; font-size: 13px; line-height: 1.6; color: #475569; text-align: center;">
                                Dear {{ $business->owner_name ?: $business->business_name }},<br><br>
                                Please renew your annual business listing fee for <strong>{{ $business->business_name }}</strong> using the secure payment link below.
                            </p>

                            <!-- Amount -->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: #f1f5f9; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 14px 32px; font-size: 26px; font-weight: 900; color: #0f172a;">
                                            ₹{{ number_format((float) $link->amount, 2) }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Pay Now button -->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding-top: 28px;">
                                        <a href="{{ $link->razorpay_link_url }}" target="_blank" style="display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 800; padding: 14px 36px; border-radius: 10px;">
                                            Pay Now
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 28px 0 0 0; font-size: 12px; line-height: 1.6; color: #b45309; text-align: center; font-weight: 700;">
                                This link expires on {{ $link->expires_at->format('d-M-Y h:i A') }} (24 hours from generation).
                            </p>

                            <p style="margin: 20px 0 0 0; font-size: 11px; line-height: 1.6; color: #64748b; text-align: center;">
                                If you have already paid or believe this is a mistake, please contact our office.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 11px; font-weight: 600;">
                            &copy; {{ date('Y') }} Shree Satwara Gnati Mandal, Ahmedabad. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
