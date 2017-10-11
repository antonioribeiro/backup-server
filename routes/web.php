<?php

use App\Services\Backup;
use Illuminate\Console\Scheduling\Schedule;

Route::get('/', function () {
    return view('disabled');
});

Route::get('/backup', function () {
    foreach (config('backup.databases') as $database) {
        app(Schedule::class)->call(function() use ($database) {
            app(Backup::class)->executeAndKeepMany($database);
        })->cron($database['cron']);

        app(Schedule::class)->call(function() use ($database) {
            app(Backup::class)->executeAndKeepOne($database);
        })->everyThirtyMinutes();
    }

    app(Backup::class)->executeAndKeepOne($database);
});

