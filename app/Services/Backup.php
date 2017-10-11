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

    protected $localFilename;

    protected $remoteFilename;

    protected $remoteFilenameOld;

    protected $deletePrevious;

    protected $database;

    /**
     * @param BackupProcedure $backupProcedure
     * @param DatabaseProvider $databases
®     * @param FilesystemProvider $filesystems
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
                config('backup.local_disk'),
                $this->localFilename
            )
        ];

        dd($destinations);

        $this->backupProcedure->run(
            $this->database['connection'],
            $destinations,
            $this->database['compression'],
            $this->database['database'] // databaseName
        );
    }

    private function contentsHaveChaged()
    {
        return true;
    }

    private function deleteOld()
    {
        Storage::disk($this->database['disk'])->delete($this->remoteFilenameOld);
    }

    protected function execute()
    {
        $this->makeFilename();

        if ($this->contentsHaveChaged()) {
            return $this->executeBackup();
        }

        $this->deduplicate();
    }

    public function executeAndKeepMany($db)
    {
        $this->executeAndKeep('hourly', $db, false);
    }

    public function executeAndKeepOne($db)
    {
        $this->executeAndKeep('minutely', $db, true);
    }

    public function executeAndKeep($type, $db, $keep)
    {
        $this->type = $type;

        $this->deletePrevious = $keep;

        $this->database = $db;

        $this->execute();
    }

    /**
     * @return bool
     */
    protected function executeBackup()
    {
        $this->renameToOld();

        $this->backup();

        $this->deleteOld();

        return true;
    }

    private function makeFilename()
    {
        $now = Carbon::now();

        $date = $now->format('Y-m-d\TH:i:s');

        $year = $now->format('Y');

        $month = $now->format('m');

        $day = $now->format('d');

        $extension = config('backup.extension', 'backup.sql');

        $this->localFilename = tempnam(sys_get_temp_dir(), 'backup_');

        $this->remoteFilename = $this->type == 'hourly'
            ? "{$this->database['remote_path']}/{$this->type}/{$year}/{$month}/{$day}/{$this->database['namespace']}.{$this->database['domain']}.{$this->database['database']}.{$date}.{$this->database['connection']}$extension"
            : "{$this->database['remote_path']}/{$this->type}/{$this->database['namespace']}.{$this->database['domain']}.{$this->database['database']}.{$this->database['connection']}$extension";

        $this->remoteFilenameOld = "{$this->remoteFilename}.gz.$date";
    }

    private function renameToOld()
    {
        if (!$this->deletePrevious) {
            return false;
        }

        if (Storage::disk($this->database['disk'])->exists($path = $this->remoteFilename.'.gz', $this->remoteFilenameOld))
        {
            Storage::disk($this->database['disk'])->move($path);
        }
    }
}
