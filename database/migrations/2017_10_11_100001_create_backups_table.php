<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBackupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('backups', function (Blueprint $table) {
            $table->increments('id');

            $table->string('filename', 8192);
            $table->string('filename_sha1')->index();
            $table->string('contents_sha1')->index();
            $table->string('remote_url', 8192);
            $table->string('namespace');
            $table->string('domain');
            $table->string('database');
            $table->string('connection');
            $table->string('disk');
            $table->string('server');
            $table->string('remote_path');

            $table->integer('duplicate_of_id')->nullable()->unsigned();

            $table->timestamp('created_at')->index();
            $table->timestamp('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backups');
    }
}
