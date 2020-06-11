<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDspBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dsp_budgets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('booking_id')->unsigned();
            $table->foreign('booking_id')->references('id')
                ->on('booking_details')->onDelete('cascade');

            $table->integer('dsp_id')->unsigned();
            $table->foreign('dsp_id')->references('id')
                ->on('dsps')->onDelete('cascade');

            $table->decimal('budget', 9, 2)->default('0.00');

            $table->string('io_host_links')->nullable();
            $table->string('dds_code')->nullable();
            $table->string('io_file_name')->nullable();
            $table->string('io_location')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('dsp_budgets');
    }
}
