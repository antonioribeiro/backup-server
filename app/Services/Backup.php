<?php

namespace App\Services;

use App\Data\Models\Backup as BackupModel;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\Destination;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Procedures\BackupProcedure;
use Storage;
use Carbon\Carbon;

class Backup
{
    protected $type;

    protected $filename;

    protected $remoteFilename;

    protected $remoteFilenameOld;

    protected $deletePrevious;

    protected $database;

    private $localFilename;

    private $remoteFileUrl;

    private $filenameUncompressed;

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
                config('backup.local_disk'),
                $this->filenameUncompressed
            )
        ];

        $this->backupProcedure->run(
            $this->database('connection'),
            $destinations,
            $this->database('compression'),
            $this->database('database') // databaseName
        );
    }

    /**
     * @param $backup
     * @return mixed
     */
    private function createDeduplicatedCopy($backup)
    {
        $new = new BackupModel();

        $new->fill($backup->toArray());

        $new->duplicate_of_id = $backup->id;

        $new->save();

        return $backup;
    }

    private function database($key)
    {
        return array_get($this->database, $key);
    }

    private function deDuplicate()
    {
        $contents_sha1 = $this->getSha1();

        if ($this->database('deduplicate') && $backup = BackupModel::where('contents_sha1', $contents_sha1)->first()) {
            return $this->createDeduplicatedCopy($backup);
        }

        return BackupModel::create([
            'filename' => $this->remoteFilename,
            'filename_sha1' => sha1($this->remoteFilename),
            'contents_sha1' => $contents_sha1,
            'remote_url'  => $this->database('remote_path'),
            'namespace' => $this->database('namespace'),
            'domain' => $this->database('domain'),
            'database' => $this->database('database'),
            'connection' => $this->database('connection'),
            'disk' => $this->database('disk'),
            'server' => $this->database('server'),
            'remote_path' => $this->remoteFileUrl,
        ]);
    }

    private function decompress($compressed)
    {
        if (!ends_with($compressed, '.gz')) {
            return $compressed;
        }

        $decompressed = $compressed . '.decompressed';

        // Raising this value may increase performance
        $buffer_size = 4096; // read 4kb at a time

        // Open our files (in binary mode)
        $file = gzopen($compressed, 'rb');
        $out_file = fopen($decompressed, 'wb');

        // Keep repeating until the end of the input file
        while (!gzeof($file)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and binary-safe
            fwrite($out_file, gzread($file, $buffer_size));
        }

        // Files are done, close files
        fclose($out_file);
        gzclose($file);

        return $decompressed;
    }

    private function deleteOld()
    {
        Storage::disk($this->database('disk'))->delete($this->remoteFilenameOld);
    }

    protected function execute()
    {
        $this->makeFilenames();

        $this->executeBackup();

        if (is_null(($backup = $this->deDuplicate())->duplicate_backup_id)) {
            $this->moveBackupToCloud();
        }
    }

    public function executeAndKeepAll($db, $key)
    {
        $this->executeAndKeep($key, $db, false);
    }

    public function executeAndKeepOne($db, $key)
    {
        $this->executeAndKeep($key, $db, true);
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

    /**
     * Get backup manager config.
     *
     * @param null $key
     * @return array|string
     */
    private function getBackupManagerConfig($key = null, $disk = null)
    {
        $disk = $disk ?: config('backup.local_disk');

        $config = config('backup-manager.' . $disk);

        if (!is_null($key)) {
            return array_get($config, $key);
        }

        return $config;
    }

    private function getBucketPath()
    {
        if ($path = $this->getBackupManagerConfig('bucket', $this->database('disk'))) {
            return $path;
        }

        return '';
    }

    private function getCompressedExtension($database)
    {
        if ($database['compression'] == 'gzip') {
            return '.gz';
        }

        return '';
    }

    /**
     * @return string
     */
    private function getSha1()
    {
        $decompressed = $this->decompress($compressed = $this->localFilename);

        $contents_sha1 = sha1_file($decompressed);

        if ($decompressed !== $compressed) {
            unlink($decompressed);
        }

        return $contents_sha1;
    }

    /**
     * @return string
     */
    private function makeExtension($compressed = true)
    {
        return config('backup.extension', '.backup.sql') . ($compressed ? $this->getCompressedExtension($this->database) : '');
    }

    /**
     * @param $extension
     */
    private function makeFilename($extension)
    {
        // tmp_oKx5N945F3nL4qjJ.backup.sql.gz

        $baseName = 'tmp_' . str_random(16);

        $this->filenameUncompressed =  $baseName . $this->makeExtension(false);

        $this->filename = $baseName . $this->makeExtension(true);
    }

    private function makeFilenames()
    {
        $now = Carbon::now();

        $date = $now->format('Y-m-d\TH:i:s');

        $year = $now->format('Y');

        $month = $now->format('m');

        $day = $now->format('d');

        $extension = $this->makeExtension();

        $this->makeFilename($extension);

        $this->makeLocalFilename();

        $this->makeRemoteFilename($year, $month, $day, $date, $extension);

        $this->makeRemoteFilenameOld($date);

        $this->makeRemoteFileUrl();
    }

    private function makeLocalFilename()
    {
        // --> /Users/antoniocarlos/code/pragmarx/backup/storage/backup/tmp_oKx5N945F3nL4qjJ.backup.sql.gz
        $this->localFilename =
            $this->getBackupManagerConfig('root') .
            DIRECTORY_SEPARATOR .
            $this->filename;
    }

    private function makeRemoteFileUrl()
    {
        $this->remoteFileUrl = Storage::disk($this->database('disk'))->url($this->getBucketPath() . $this->remoteFilename);

        $this->remoteFileUrl = str_replace('//'.$this->getBucketPath().'.', '//', $this->remoteFileUrl);
    }

    /**
     * @param $year
     * @param $month
     * @param $day
     * @param $date
     * @param $extension
     */
    private function makeRemoteFilename($year, $month, $day, $date, $extension)
    {
        // --> /backup/databases/minutely/pragmarx.www.pragmarx.com.pragmarx.pgsql.backup.sql.gz
        $this->remoteFilename =
            $this->type == 'hourly'
                ? "{$this->database('remote_path')}/{$this->type}/{$year}/{$month}/{$day}/{$this->database('namespace')}.{$this->database('domain')}.{$this->database('database')}.{$date}.{$this->database('connection')}$extension"
                : "{$this->database('remote_path')}/{$this->type}/{$this->database('namespace')}.{$this->database('domain')}.{$this->database('database')}.{$this->database('connection')}$extension";
    }

    /**
     * @param $date
     */
    private function makeRemoteFilenameOld($date)
    {
        // --> /backup/databases/minutely/pragmarx.www.pragmarx.com.pragmarx.pgsql.backup.sql.gz.2017-10-17T22:57:56
        $this->remoteFilenameOld = "{$this->remoteFilename}.$date";
    }

    private function moveBackupToCloud()
    {
        Storage::disk($this->database('disk'))->put($this->remoteFilename, file_get_contents($this->localFilename));

        unlink($this->localFilename);
    }

    private function renameToOld()
    {
        if (!$this->deletePrevious) {
            return false;
        }

        if (Storage::disk($disk = $this->database('disk'))->exists($this->remoteFilename))
        {
            Storage::disk($disk)->move($this->remoteFilename, $this->remoteFilenameOld);
        }
    }
}
