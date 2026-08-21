<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiry</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 0; }
        .wrapper { max-width: 580px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; }
        .header { background: linear-gradient(135deg, #7c3aed, #4f46e5); padding: 32px 32px 24px; text-align: center; }
        .header img { width: 52px; height: 52px; border-radius: 12px; margin-bottom: 12px; }
        .header h1 { color: #fff; font-size: 20px; font-weight: 800; margin: 0; letter-spacing: -0.3px; }
        .header p { color: rgba(255,255,255,0.75); font-size: 12px; margin: 6px 0 0; }
        .body { padding: 28px 32px; }
        .badge { display: inline-block; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 99px; font-size: 11px; font-weight: 700; padding: 4px 12px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 0.5px; }
        .greeting { font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
        .field-row { margin-bottom: 16px; }
        .field-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .field-value { font-size: 13px; font-weight: 600; color: #1e293b; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; word-break: break-word; }
        .message-box { font-size: 13px; color: #334155; background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #7c3aed; border-radius: 10px; padding: 14px 16px; line-height: 1.7; white-space: pre-wrap; word-break: break-word; }
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 24px 0; }
        .reply-note { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px 16px; font-size: 11px; color: #1d4ed8; font-weight: 600; }
        .footer { text-align: center; padding: 20px 32px 28px; font-size: 11px; color: #94a3b8; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>📩 New Contact Inquiry</h1>
            <p>Received from the Community Portal Contact Form</p>
        </div>

        <!-- Body -->
        <div class="body">
            <span class="badge">✅ New Message</span>
            <p class="greeting">Hello Admin,</p>
            <p style="font-size:13px;color:#475569;margin:0 0 20px;">A new inquiry has been submitted through the <strong>Contact Us</strong> form. Here are the details:</p>

            <div class="field-row">
                <div class="field-label">Sender Name</div>
                <div class="field-value">{{ $senderName }}</div>
            </div>

            <div class="field-row">
                <div class="field-label">Email Address</div>
                <div class="field-value"><a href="mailto:{{ $senderEmail }}" style="color:#7c3aed;text-decoration:none;">{{ $senderEmail }}</a></div>
            </div>

            <div class="field-row">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $mailSubject }}</div>
            </div>

            <div class="field-row">
                <div class="field-label">Message</div>
                <div class="message-box">{{ $userMessage }}</div>
            </div>

            <hr class="divider">

            <div class="reply-note">
                💬 To reply directly, simply click <strong>Reply</strong> in your email client — the sender's email is already set as the Reply-To address.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <strong>Satwara Community Portal</strong><br>
            This is an automated notification email. Please do not reply to this address.
        </div>
    </div>
</body>
</html>
