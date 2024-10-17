<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('app:collect')->everyMinute();

Schedule::command('app:update-payment-status')
    ->everyMinute()
    ->appendOutputTo('storage/logs/collect.log');
