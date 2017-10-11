<?php

namespace App\Services;

use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\Destination;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Procedures\BackupProcedure;
use Storage;
use Carbon\Carbon;

class Backup
{
    protected $type;

    protected $remoteFilename;

    protected $remoteFilenameOld;

    protected $deletePrevious;

    protected $database;

    /**
     * @param BackupProcedure $backupProcedure
     * @param DatabaseProvider $databases
     * @param FilesystemProvider $filesystems
     */
    public function __construct(BackupProcedure $backupProcedure, DatabaseProvider $databases, FilesystemProvider $filesystems) {
        $this->backupProcedure = $backupProcedure;

        $this->databases = $databases;

        $this->filesystems = $filesystems;
    }

    private function backup()
    {
        $destinations = [
            new Destination(
                $this->database['disk'],
                $this->remoteFilename
            )
        ];

        $this->backupProcedure->run(
            $this->database['connection'],
            $destinations,
            $this->database['compression'],
            $this->database['database'] // databaseName
        );
    }

    private function deleteOld()
    {
        Storage::disk($this->database['disk'])->delete($this->remoteFilenameOld);
    }

    protected function execute()
    {
        $this->makeFilename();

        $this->renameToOld();

        $this->backup();

        $this->deleteOld();
    }

    public function executeAndKeepMany($db)
    {
        $this->type = 'hourly';

        $this->deletePrevious = false;

        $this->database = $db;

        $this->execute($this->database, false);
    }

    public function executeAndKeepOne($db)
    {
        $this->type = 'minutely';

        $this->deletePrevious = true;

        $this->database = $db;

        $this->execute($this->database, true);
    }

    private function makeFilename()
    {
        $now = Carbon::now();

        $date = $now->format('Y-m-d\TH:i:s');

        $year = $now->format('Y');

        $month = $now->format('m');

        $day = $now->format('d');

        $this->remoteFilename = $this->type == 'hourly'
            ? "{$this->database['remote_path']}/{$this->type}/{$year}/{$month}/{$day}/{$this->database['namespace']}.{$this->database['domain']}.{$this->database['database']}.{$date}.{$this->database['connection']}.backup.sql"
            : "{$this->database['remote_path']}/{$this->type}/{$this->database['namespace']}.{$this->database['domain']}.{$this->database['database']}.{$this->database['connection']}.backup.sql";

        $this->remoteFilenameOld = "{$this->remoteFilename}.gz.$date";

    }

    private function renameToOld()
    {
        if (!$this->deletePrevious) {
            return false;
        }

        Storage::disk($this->database['disk'])->move($this->remoteFilename.'.gz', $this->remoteFilenameOld);
    }
}
