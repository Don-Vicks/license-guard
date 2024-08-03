<x-mail::message>
<x-mail::panel>
Your payment of {{ $amount }} NGN for the activation/renewal of your License {{ $licenseUser->link }} via Flutterwave was successful. You may continue enjoying our services once again.
Your license would expire on {{ $value }}.
</x-mail::panel>

<x-mail::button :url="$url">
View Payments
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
