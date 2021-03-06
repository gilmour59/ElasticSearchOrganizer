<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArchiveFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('div_name', 100);
        });

        Schema::create('archive_files', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->longText('content')->nullable();
            $table->string('file_name', 100);
            $table->string('file', 100);
            $table->timestamps();

            $table->unsignedInteger('division_id');
            $table->foreign('division_id')->references('id')->on('divisions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_files');
        Schema::dropIfExists('divisions');
    }
}
