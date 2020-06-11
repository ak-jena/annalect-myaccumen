<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')
                ->on('campaigns')->onDelete('cascade');

            $table->integer('booking_status_id')->unsigned();
            $table->foreign('booking_status_id')->references('id')
                ->on('booking_statuses')->onDelete('cascade');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');

            $table->string('pricing_model')->nullable();
            $table->boolean('has_budget_silos')->nullable();
            $table->json('budget_silos')->nullable();
            $table->decimal('budget_silos_total', 7, 2)->default('0.00')->nullable();

            $table->string('targeting_requirements')->nullable();
            $table->boolean('has_onsite_tracking_pixel')->nullable();

            $table->string('tracking_pixel_details')->nullable();
            $table->string('tracking_pixel_events')->nullable();

            $table->json('tracking_tag')->nullable();
            $table->boolean('is_rich_media')->nullable();

            $table->json('rm_creative_format')->nullable();
            $table->string('rm_creative_format_other')->nullable();
            $table->string('rm_creative_notes')->nullable();

            $table->boolean('is_1x1_supplied')->nullable();

            $table->json('supplied_creative_formats')->nullable();
            $table->boolean('specific_activity_tags')->nullable();

            $table->boolean('is_reporting')->nullable();

            $table->string('weekly_updates')->nullable();
            $table->string('metrics_required')->nullable();

            $table->string('adserver')->nullable();
            $table->string('adserver_metric')->nullable();

            $table->string('site_list')->nullable();

            $table->string('audience_segment_examples')->nullable();

            $table->string('other_info')->nullable();
            $table->string('omg_programmatic_assessment')->nullable();

            $table->boolean('requested_tracking_pixels')->nullable();
            $table->boolean('implemented_pixels')->nullable();

            $table->string('data_collection_code')->nullable();
            $table->json('tracking_tag_dsp')->nullable();

            $table->string('1x1_adserver_trackers')->nullable();
            $table->string('reporting_description')->nullable();

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
        Schema::dropIfExists('booking_details');
    }
}
