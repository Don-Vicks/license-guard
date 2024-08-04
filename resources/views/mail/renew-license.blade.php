<x-mail::message>
# License Update

## Hello there,
You have license (s) which expired today, kindly login to renew them in order to keep enjoying our services

<x-mail::button :url="url('/user')">
Login
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
