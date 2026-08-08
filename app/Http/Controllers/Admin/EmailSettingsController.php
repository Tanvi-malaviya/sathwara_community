<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailSettingsController extends Controller
{
    /**
     * Display Email Settings Form
     */
    public function index()
    {
        $emailSettings = [
            'mail_mailer' => Setting::get('mail_mailer', env('MAIL_MAILER', 'smtp')),
            'mail_host' => Setting::get('mail_host', env('MAIL_HOST', 'smtp.gmail.com')),
            'mail_port' => Setting::get('mail_port', env('MAIL_PORT', '587')),
            'mail_username' => Setting::get('mail_username', env('MAIL_USERNAME', '')),
            'mail_password' => Setting::get('mail_password', env('MAIL_PASSWORD', '')),
            'mail_encryption' => Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'tls')),
            'mail_from_address' => Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'noreply@sathwaracommunity.com')),
            'mail_from_name' => Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'Sathwara Community Portal')),
        ];

        return view('admin.settings.email', compact('emailSettings'));
    }

    /**
     * Update Email / SMTP Settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|numeric',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|max:20',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $fields = [
            'mail_mailer' => $request->mail_mailer,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username ?? '',
            'mail_password' => $request->mail_password ?? '',
            'mail_encryption' => $request->mail_encryption ?? 'tls',
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
        ];

        // 1. Save to Database Settings
        foreach ($fields as $key => $val) {
            Setting::set($key, $val);
        }

        // 2. Update .env File
        $envUpdates = [
            'MAIL_MAILER' => $request->mail_mailer,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username ?? '',
            'MAIL_PASSWORD' => $request->mail_password ?? '',
            'MAIL_ENCRYPTION' => $request->mail_encryption ?? 'null',
            'MAIL_FROM_ADDRESS' => '"' . $request->mail_from_address . '"',
            'MAIL_FROM_NAME' => '"' . str_replace('"', '\"', $request->mail_from_name) . '"',
        ];

        $this->updateEnvFile($envUpdates);

        return redirect()->back()
            ->with('success', __('messages.email_settings_updated_successfully') ?? 'Email settings updated successfully and saved to .env!')
            ->with('active_settings_tab', 'email');
    }

    /**
     * Send Test Email using current settings
     */
    public function sendTestMail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email|max:255',
        ]);

        try {
            // Apply Dynamic Mail Config for Test
            $mailer = Setting::get('mail_mailer', env('MAIL_MAILER', 'smtp'));
            $host = Setting::get('mail_host', env('MAIL_HOST', 'smtp.gmail.com'));
            $port = Setting::get('mail_port', env('MAIL_PORT', '587'));
            $username = Setting::get('mail_username', env('MAIL_USERNAME', ''));
            $password = Setting::get('mail_password', env('MAIL_PASSWORD', ''));
            $encryption = Setting::get('mail_encryption', env('MAIL_ENCRYPTION', 'tls'));
            $fromAddress = Setting::get('mail_from_address', env('MAIL_FROM_ADDRESS', 'noreply@sathwaracommunity.com'));
            $fromName = Setting::get('mail_from_name', env('MAIL_FROM_NAME', 'Sathwara Community Portal'));

            config([
                'mail.default' => $mailer,
                'mail.mailers.smtp.host' => $host,
                'mail.mailers.smtp.port' => $port,
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.encryption' => ($encryption === 'null' || strtolower($encryption) === 'none') ? null : $encryption,
                'mail.from.address' => $fromAddress,
                'mail.from.name' => $fromName,
            ]);

            $recipient = $request->test_email;

            Mail::raw("Hello! This is a test email sent from " . $fromName . " to verify your SMTP configuration settings. If you received this email, your email configuration is working perfectly!", function ($message) use ($recipient, $fromName, $fromAddress) {
                $message->to($recipient)
                    ->subject("Sathwara Community - SMTP Test Email");
            });

            return redirect()->back()
                ->with('success', __('messages.test_email_sent_successfully', ['email' => $recipient]) ?? "Test email sent successfully to {$recipient}!")
                ->with('active_settings_tab', 'email');

        } catch (\Throwable $e) {
            Log::error("SMTP Test Email Error: " . $e->getMessage());
            return redirect()->back()
                ->with('error', "Failed to send test email. Error: " . $e->getMessage())
                ->with('active_settings_tab', 'email');
        }
    }

    /**
     * Helper to update .env file keys
     */
    private function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Check if key exists
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
