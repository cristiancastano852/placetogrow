@component('mail::message')
# {{ __('invoice.expiration_reminder.greeting') }}

{{ __('invoice.expiration_reminder.message', ['date' => $invoice->expiration_date->format('d/m/Y')]) }}

{{ __('invoice.expiration_reminder.reminder') }}

{{ __('invoice.expiration_reminder.warning') }}

@component('mail::button', ['url' => route('invoice.index', ['microsite' => $invoice->microsite->id])])
    {{ __('invoice.expiration_reminder.button') }}
@endcomponent

{{ __('invoice.expiration_reminder.footer') }}

{{ __('invoice.expiration_reminder.salutation') }},
{{ $invoice->microsite->name }}
@endcomponent
