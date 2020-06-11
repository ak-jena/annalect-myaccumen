<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AmendCreativeTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('tags', function (Blueprint $table) {
            //
            $table->dropColumn('fileshare_links');
            $table->string('file_type')->after('location')->default('creative tag');
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
        Schema::table('tags', function (Blueprint $table) {
            //
            $table->string('fileshare_links');
            $table->dropColumn('file_type');
        });

    }
}
