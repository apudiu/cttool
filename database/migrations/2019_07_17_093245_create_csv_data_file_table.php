<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvDataFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_data_file', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('csv_data_id');
            $table->unsignedBigInteger('file_id');

            $table->timestamps();

            $table->foreign('csv_data_id')->references('id')->on('csv_data')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_data_file');
    }
}
