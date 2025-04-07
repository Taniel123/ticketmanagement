@component('mail::message')
# {{ $subject }}

{!! $content !!}

@component('mail::button', ['url' => $url])
View Ticket
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent