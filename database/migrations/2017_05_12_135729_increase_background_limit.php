<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseBackgroundLimit extends Migration
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

        Schema::table('briefs', function (Blueprint $table) {
            //
            $table->string('background', 1500)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('briefs', function (Blueprint $table) {
            //
            $table->string('background', 255)->change();
        });
    }
}
