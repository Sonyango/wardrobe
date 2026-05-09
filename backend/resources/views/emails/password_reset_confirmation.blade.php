@component('mail::message')
# Password Reset Successful

Hello {{ $name }},

Password for your {{ config('app.name') }} account has been successfully reset. You can now log in with your new password.

If you did not perform this action, please contact our support team immediately.

Thanks,<br>
{{ config('app.name') }} Team.
@endcomponent
