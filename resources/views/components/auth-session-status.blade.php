@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }}>
        @if($status == 'We have emailed your password reset verification code.')
            {{ __('messages.otp_email_sent_status') }}
        @elseif($status == 'Code verified. You can now reset your password.')
            {{ __('messages.otp_code_verified_status') }}
        @elseif($status == 'Your password has been successfully reset. You can now log in.')
            {{ __('messages.password_reset_success_status') }}
        @else
            {{ $status }}
        @endif
    </div>
@endif
