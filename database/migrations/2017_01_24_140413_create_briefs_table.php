<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBriefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('briefs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')
                ->on('campaigns')->onDelete('cascade');

            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')->references('id')
                ->on('clients')->onDelete('cascade');

            $table->string('campaign_name')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('flighting_considerations');
            $table->string('background')->nullable();
            $table->string('target_audience_profile')->nullable();
            $table->string('additional_info')->nullable();

            $table->boolean('is_stack_client')->nullable();
            $table->json('google_audiences')->nullable();

            $table->string('file_name')->nullable();
            $table->string('location')->nullable();

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
        //
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('briefs');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
