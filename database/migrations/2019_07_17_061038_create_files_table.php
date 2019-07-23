<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('user_id');
            $table->integer('import_batch');

            $table->string('name')->comment('file name');
            $table->string('path')->comment('full name');

            $table->timestamps();

            // FK constraints
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('import_batch')->references('import_batch')->on('csv_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
