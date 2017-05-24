<?php

namespace App\Services;

use Carbon\Carbon;
use Storage;

class DeleteGzipFiles
{
    public function delete()
    {
        collect(Storage::files('/'))->each(function ($file) {
            if (ends_with($file, '.gz')) {
                if ($this->getFileAgeInDays($file) > 1) {
                    Storage::delete($file);
                }
            }
        });
    }

    private function getFileAgeInDays($file)
    {
        return Carbon::createFromTimestampUTC(
            Storage::lastModified($file)
        )->diffInDays(Carbon::now());
    }
}
