@component('mail::message')
# Dear {{ $user->name }}

Use the below code to login

Vote code - {{ $passcode }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent
