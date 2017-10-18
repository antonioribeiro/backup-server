<?php

namespace App\Services\BackupManager;

use BackupManager\Databases;
use BackupManager\Filesystems;
use BackupManager\Compressors;
use BackupManager\Config\Config;
use Illuminate\Support\ServiceProvider;
use App\Services\BackupManager\Manager;

class ManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBackupManager();
    }

    public function instantiate()
    {
        // build providers
        $filesystems = new Filesystems\FilesystemProvider(Config::fromPhpFile('config/storage.php'));
        $filesystems->add(new Filesystems\Awss3Filesystem);
        $filesystems->add(new Filesystems\GcsFilesystem);
        $filesystems->add(new Filesystems\DropboxFilesystem);
        $filesystems->add(new Filesystems\FtpFilesystem);
        $filesystems->add(new Filesystems\LocalFilesystem);
        $filesystems->add(new Filesystems\RackspaceFilesystem);
        $filesystems->add(new Filesystems\SftpFilesystem);

        $databases = new Databases\DatabaseProvider(Config::fromPhpFile('config/database.php'));
        $databases->add(new Databases\MysqlDatabase);
        $databases->add(new Databases\PostgresqlDatabase);

        $compressors = new Compressors\CompressorProvider;
        $compressors->add(new Compressors\GzipCompressor);
        $compressors->add(new Compressors\NullCompressor);

        return new Manager($filesystems, $databases, $compressors);
    }
}
