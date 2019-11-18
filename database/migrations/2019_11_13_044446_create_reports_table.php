<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // pulling report statuses from configuration
        $reportStatuses = config('app.report.status');

        Schema::create('reports', function (Blueprint $table) use($reportStatuses) {
            $table->bigIncrements('id');

            $table->string('name')->comment('File name');
            $table->enum('status', $reportStatuses)->comment('Upload status');
            $table->string('reason')->nullable()->comment('File upload fail reason');
            $table->dateTime('time')->comment('Time reported by log file');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
