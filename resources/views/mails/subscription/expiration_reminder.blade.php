@component('mail::message')
# Hola {{ $subscription->user->name }},

Tu subscripción a nuestro plan **{{ $subscription->plan->name }}** está a punto de expirar. Por favor, asegúrate de renovar tu suscripción para evitar interrupciones.



@component('mail::button', ['url' => route('subscriptions.index')])
    Renovar ahora
@endcomponent

Gracias por confiar en nosotros.

Saludos cordiales,
{{ $subscription->microsite->name }}
@endcomponent
