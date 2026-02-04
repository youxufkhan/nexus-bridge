<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\IntegrationConnection;
use App\Jobs\FetchOrdersJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    IntegrationConnection::whereNotNull('credentials')->each(function ($connection) {
            FetchOrdersJob::dispatch($connection);
        }
        );
    })->everyFifteenMinutes();