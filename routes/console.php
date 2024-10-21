<?php

use App\Jobs\SendInvoiceDueAlertJob;
use App\Jobs\SendSubscriptionExpiryAlertJob;
use App\Jobs\SendSubscriptionNextCollectJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:collect')->daily()->at('00:00');

Schedule::command('app:check-subscriptions-pending')
    ->everyFiveMinutes();

Schedule::command('app:update-payment-status')
    ->everyFiveMinutes();

Schedule::job((new SendSubscriptionExpiryAlertJob()))
    ->daily();
Schedule::job((new SendInvoiceDueAlertJob()))
    ->daily();
Schedule::job((new SendSubscriptionNextCollectJob()))
    ->daily();
