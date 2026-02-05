<?php

use App\Jobs\FetchOrdersJob;
use App\Models\IntegrationConnection;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    IntegrationConnection::whereNotNull('credentials')->each(function ($connection) {
        FetchOrdersJob::dispatch($connection);
    }
    );
})->hourly();

Schedule::call(function () {
    IntegrationConnection::whereNotNull('credentials')->each(function ($connection) {
        \App\Jobs\FetchProductsJob::dispatch($connection);
    }
    );
})->daily();
