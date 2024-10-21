@component('mail::message')
# {{ __('subscription.next_collect_alert_mail.greeting', ['name' => $subscription->user->name]) }}

{{ __('subscription.next_collect_alert_mail.message', [
    'date' => $subscription->next_billing_date->format('d/m/Y'),
    'microsite' => $subscription->microsite->name,
    'plan' => $subscription->plan->name
]) }}

@component('mail::button', ['url' => route('subscriptions.index')])
    {{ __('subscription.next_collect_alert_mail.button') }}
@endcomponent

{{ __('subscription.next_collect_alert_mail.footer', ['app_name' => config('app.name')]) }}

@endcomponent
