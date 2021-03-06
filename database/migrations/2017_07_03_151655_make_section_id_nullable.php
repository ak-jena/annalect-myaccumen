<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeSectionIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // doctrine does not support json, so following is a hack from: https://github.com/laravel/framework/issues/1186
        Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');

        Schema::table('statuses', function (Blueprint $table) {
            //
            $table->integer('section_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            //
            // doctrine does not support json, so following is a hack from: https://github.com/laravel/framework/issues/1186
            Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('json', 'string');

            $table->integer('section_id')->unsigned()->nullable(false)->change();
        });
        //

    }
}
