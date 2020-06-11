<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseLimits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // doctrine does not support json, so following is a hack from: https://github.com/laravel/framework/issues/1186
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');

        Schema::table('campaigns_products', function (Blueprint $table) {
            //
            $table->string('geo_targeting_details', 2500)->change();
            $table->string('specific_activity_response', 2500)->change();
            $table->string('contextual_env_pp_response', 2500)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // doctrine does not support json, so following is a hack from: https://github.com/laravel/framework/issues/1186
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');

        Schema::table('campaigns_products', function (Blueprint $table) {
            //
            $table->string('geo_targeting_details', 255)->change();
            $table->string('specific_activity_response', 255)->change();
            $table->string('contextual_env_pp_response', 255)->change();
        });
    }
}
