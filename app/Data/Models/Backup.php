<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'filename_sha1',
        'contents_sha1',
        'remote_url',
        'namespace',
        'domain',
        'database',
        'connection',
        'disk',
        'server',
        'remote_path',
    ];
}
