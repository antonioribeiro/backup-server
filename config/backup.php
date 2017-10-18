<?php

return [

    'local_disk' => 'backup', /// config/filesystems

    'extension' => '.backup.sql',

    'databases' => [
        'pragmarx' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '0 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'pragmarx',
            'domain' => 'www.pragmarx.com',
            'database' => 'pragmarx', //'pragmarx_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'parlamentojuvenil' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '0 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'parlamentojuvenil',
            'domain' => 'www.parlamento-juvenil.rj.gov.br',
            'database' => 'parlamentojuvenil_20171005', //'parlamentojuvenil_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],


        'bureau' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '5 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'bureau',
            'domain' => 'bureau.io',
            'database' => 'bureau_staging',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'carteiradadobem' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '10 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'carteiradadobem',
            'domain' => 'carteiradadobem.com.br',
            'database' => 'carteiradadobem_staging',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'nucleoconstelar' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '15 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'nucleoconstelar',
            'domain' => 'nucleoconstelar.com',
            'database' => 'nucleoconstelar_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'nucleoconstelar' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '20 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'nucleoconstelar',
            'domain' => 'nucleoconstelar.com',
            'database' => 'nucleoconstelar_staging',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'kallzenter' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '25 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'kallzenter',
            'domain' => 'kallzenter.com',
            'database' => 'kallzenter_staging',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],


        'kallzenter' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '30 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'kallzenter',
            'domain' => 'kallzenter.com',
            'database' => 'kallzenter_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],


        'crm' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '35 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'crm',
            'domain' => 'crm.antoniocarlosribeiro.com',
            'database' => 'crm_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],

        'antoniocarlosribeiro.com' => [
            'schedule' => [
                'daily' => [
                    'frequency' => ['cron', '40 */4 * * *'], // every 4 hours // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'all',
                ],
                'most-recent' => [
                    'frequency' => ['everyFiveMinutes'], // https://laravel.com/docs/5.5/scheduling#schedule-frequency-options
                    'keep' => 'one',
                ],
            ],
            'namespace' => 'antoniocarlosribeiro.com',
            'domain' => 'antoniocarlosribeiro.com',
            'database' => 'antoniocarlosribeiro_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ]

    ]

];
