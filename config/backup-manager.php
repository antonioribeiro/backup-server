<?php

return [
    'backup' => [
        'type' => 'Local',
        'root' => storage_path('backup'),
    ],

    'local' => [
        'type' => 'Local',
        'root' => storage_path('app'),
    ],

    's3' => [
        'type'   => 'AwsS3',
        'user'   => env('AWS_USER'),
        'key'    => env('AWS_KEY'),
        'secret' => env('AWS_SECRET'),
        'region' => env('AWS_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'root'   => env('AWS_ROOT'),
    ],

    'gcs' => [
        'type' => 'Gcs',
        'key'    => '',
        'secret' => '',
        'bucket' => '',
        'root'   => '',
    ],

    'rackspace' => [
        'type' => 'Rackspace',
        'username' => '',
        'key' => '',
        'container' => '',
        'zone' => '',
        'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
        'root' => '',
    ],

    'dropbox' => [
        'type' => 'Dropbox',
        'token' => '',
        'key' => '',
        'secret' => '',
        'app' => '',
        'root' => '',
    ],

    'ftp' => [
        'type' => 'Ftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 21,
        'passive' => true,
        'ssl' => true,
        'timeout' => 30,
        'root' => '',
    ],

    'sftp' => [
        'type' => 'Sftp',
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 21,
        'timeout' => 10,
        'privateKey' => '',
        'root' => '',
    ],
];
