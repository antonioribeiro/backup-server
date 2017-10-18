<?php

use App\Services\Backup;
use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('db:delete-gzip')->daily();

foreach (config('backup.databases') as $database) {
    collect($database['schedule'])->each(function($schedule, $key) use ($database) {
        $scheduleCommand = is_array($schedule['frequency']) ? $schedule['frequency'][0] : $schedule['frequency'];

        $parameter = isset($schedule['frequency'][1]) ? $schedule['frequency'][1] : null;

        $keep = $schedule['keep'] == 'one' ? 'executeAndKeepOne' : 'executeAndKeepAll';

        app(Schedule::class)->call(function() use ($database, $keep, $key) {
            app(Backup::class)->{$keep}($database, $key);
        })->{$scheduleCommand}($parameter);
    });
}
