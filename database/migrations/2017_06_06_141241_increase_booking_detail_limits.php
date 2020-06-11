<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseBookingDetailLimits extends Migration
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

        Schema::table('booking_details', function (Blueprint $table) {
            //
            $table->text('targeting_requirements')->change();
            $table->text('tracking_pixel_details')->change();
            $table->text('tracking_pixel_events')->change();
            $table->text('rm_creative_format_other')->change();
            $table->text('rm_creative_notes')->change();
            $table->text('weekly_updates')->change();
            $table->text('metrics_required')->change();
            $table->text('site_list')->change();
            $table->text('audience_segment_examples')->change();
            $table->text('other_info')->change();
            $table->text('omg_programmatic_assessment')->change();
            $table->text('1x1_adserver_trackers')->change();
            $table->text('reporting_description')->change();

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

        Schema::table('booking_details', function (Blueprint $table) {
            //
            $table->string('targeting_requirements', 255)->change();
            $table->string('tracking_pixel_details', 255)->change();
            $table->string('tracking_pixel_events', 255)->change();
            $table->string('rm_creative_format_other', 255)->change();
            $table->string('rm_creative_notes', 255)->change();
            $table->string('weekly_updates', 255)->change();
            $table->string('metrics_required', 255)->change();
            $table->string('site_list', 255)->change();
            $table->string('audience_segment_examples', 255)->change();
            $table->string('other_info', 255)->change();
            $table->string('omg_programmatic_assessment', 255)->change();
            $table->string('1x1_adserver_trackers', 255)->change();
            $table->string('reporting_description', 255)->change();
        });
    }
}
