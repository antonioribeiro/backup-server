<?php

use App\Services\DeleteGzipFiles;

Artisan::command('db:delete-gzip', function () {
    app(DeleteGzipFiles::class)->delete();
})->describe('Delete old gzip files');
