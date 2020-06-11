<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGridStatusLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grid_status_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('targeting_grid_id')->unsigned();
            $table->foreign('targeting_grid_id')->references('id')->on('targeting_grids')
            ->on('targeting_grids')->onDelete('cascade');

            $table->integer('grid_status_id')->unsigned();
            $table->foreign('grid_status_id')->references('id')->on('grid_statuses')
                ->on('grid_statuses')->onDelete('cascade');



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
        Schema::dropIfExists('grid_status_logs');
    }
}
