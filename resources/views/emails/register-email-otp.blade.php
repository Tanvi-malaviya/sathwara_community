<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Registration — Email Verification OTP</title>
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
                            <p style="margin: 6px 0 0 0; font-size: 11px; color: #94a3b8; font-weight: 600;">MEMBERSHIP REGISTRATION</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="margin: 0 0 16px 0; font-size: 18px; font-weight: 800; color: #1e293b; text-align: center;">Verify Your Email Address</h2>
                            <p style="margin: 0 0 24px 0; font-size: 13px; line-height: 1.6; color: #475569; text-align: center;">
                                You are registering a new membership account with the email address <strong>{{ $email }}</strong>.
                                Please use the following 6-digit verification code to confirm your email.
                                This code is valid for <strong>10 minutes</strong>.
                            </p>

                            <!-- OTP Display -->
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: #f1f5f9; border: 2px dashed #cbd5e1; border-radius: 12px; padding: 16px 40px; font-size: 36px; font-weight: 900; letter-spacing: 0.15em; color: #0f172a;">
                                            {{ $otp }}
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Timer note -->
                            <p style="margin: 20px 0 0 0; font-size: 12px; font-weight: 700; color: #f59e0b; text-align: center;">
                                ⏱ This OTP expires in 10 minutes.
                            </p>

                            <p style="margin: 24px 0 0 0; font-size: 11px; line-height: 1.6; color: #64748b; text-align: center;">
                                If you did not initiate this registration, please ignore this email. No account will be created without completing the verification.
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
