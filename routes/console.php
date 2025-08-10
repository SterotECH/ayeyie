<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule order reversion command to run daily at 2 AM
use Illuminate\Support\Facades\Schedule;

Schedule::command('orders:revert-unpaid')
    ->dailyAt('02:00')
    ->withoutOverlapping()
    ->runInBackground();

// Schedule suspicious activity notifications to run every morning at 8 AM
Schedule::command('suspicious:notify')
    ->dailyAt('08:00')
    ->withoutOverlapping();
