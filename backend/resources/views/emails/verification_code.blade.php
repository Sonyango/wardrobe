@component('mail::message')
# Hello {{ $name }},

Thanks for registering at {{ config('app.name') }}.
Use the verification code below to complete your registration:

@component('mail::panel')
# {{ $code }}
@endcomponent

This code expires in 15 minutes.

Thanks,<br>
{{ config('app.name') }} Team.
@endcomponent
