@component('mail::message')
# Hello {{ $name }},

We received a password reset request for your {{ config('app.name') }} account.
Please use the code below to reset your password.

@component('mail::panel')
{{ $code }}
@endcomponent

This code expires in 10 minutes.
If you did not request a password reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }} Team.
@endcomponent
