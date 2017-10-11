<?php

return [

    'databases' => [

        'parlamentojuvenil' => [
            'cron' => '0 */4 * * *', // every 4 hours
            'namespace' => 'parlamentojuvenil',
            'domain' => 'www.parlamento-juvenil.rj.gov.br',
            'database' => 'parlamentojuvenil_20171005', //'parlamentojuvenil_production',
            'connection' => 'pgsql',
            'disk' => 's3',
            'server' => 'db001',
            'remote_path' => '/backup/databases',
            'compression' => 'gzip',
        ],
//
//        'bureau' => [
//            'cron' => '5 */4 * * *',
//            'namespace' => 'bureau',
//            'domain' => 'bureau.io',
//            'database' => 'bureau_staging',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//        'carteiradadobem' => [
//            'cron' => '10 */4 * * *',
//            'namespace' => 'carteiradadobem',
//            'domain' => 'carteiradadobem.com.br',
//            'database' => 'carteiradadobem_staging',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//        'nucleoconstelar' => [
//            'cron' => '15 */4 * * *',
//            'namespace' => 'nucleoconstelar',
//            'domain' => 'nucleoconstelar.com',
//            'database' => 'nucleoconstelar_production',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//        'nucleoconstelar' => [
//            'cron' => '20 */4 * * *',
//            'namespace' => 'nucleoconstelar',
//            'domain' => 'nucleoconstelar.com',
//            'database' => 'nucleoconstelar_staging',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//        'kallzenter' => [
//            'cron' => '25 */4 * * *',
//            'namespace' => 'kallzenter',
//            'domain' => 'kallzenter.com',
//            'database' => 'kallzenter_staging',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//
//        'kallzenter' => [
//            'cron' => '30 */4 * * *',
//            'namespace' => 'kallzenter',
//            'domain' => 'kallzenter.com',
//            'database' => 'kallzenter_production',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//
//        'crm' => [
//            'cron' => '35 */4 * * *',
//            'namespace' => 'crm',
//            'domain' => 'crm.antoniocarlosribeiro.com',
//            'database' => 'crm_production',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ],
//
//        'antoniocarlosribeiro.com' => [
//            'cron' => '40 */4 * * *',
//            'namespace' => 'antoniocarlosribeiro.com',
//            'domain' => 'antoniocarlosribeiro.com',
//            'database' => 'antoniocarlosribeiro_production',
//            'connection' => 'pgsql',
//            'disk' => 's3',
//            'server' => 'db001',
//            'remote_path' => '/backup/databases',
//            'compression' => 'gzip',
//        ]

    ]

];
