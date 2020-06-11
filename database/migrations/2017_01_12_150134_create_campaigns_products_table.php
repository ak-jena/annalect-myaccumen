<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns_products', function (Blueprint $table) {
            $table->integer('campaign_id')->unsigned();
            $table->foreign('campaign_id')->references('id')
                ->on('campaigns')->onDelete('cascade');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');

            $table->decimal('budget', 9, 2)->default('0.00');

            $table->string('campaign_objective')->nullable();

            $table->string('primary_metric')->nullable();
            $table->string('primary_metric_goal_value')->nullable();

            $table->string('metric_2')->nullable();
            $table->string('metric_2_goal_value')->nullable();

            $table->string('metric_3')->nullable();
            $table->string('metric_3_goal_value')->nullable();

            $table->string('metric_4')->nullable();
            $table->string('metric_4_goal_value')->nullable();

            $table->string('display_media_mobile_activity_1')->nullable();
            $table->string('display_media_mobile_metric_1')->nullable();
            $table->string('display_media_mobile_value_1')->nullable();

            $table->string('display_media_mobile_activity_2')->nullable();
            $table->string('display_media_mobile_metric_2')->nullable();
            $table->string('display_media_mobile_value_2')->nullable();

            $table->string('display_media_mobile_activity_3')->nullable();
            $table->string('display_media_mobile_metric_3')->nullable();
            $table->string('display_media_mobile_value_3')->nullable();

            $table->string('geo_targeting')->nullable();
            $table->string('geo_targeting_details')->nullable();

            $table->json('inventory_screentypes')->nullable();

            $table->string('specific_activity_response')->nullable();
            $table->string('contextual_env_pp_response')->nullable();

            $table->json('creative_lengths')->nullable();
            $table->string('creative_type')->nullable();

            $table->string('interactive_creative_provider')->nullable();

            $table->json('video_creative_type')->nullable();

            $table->string('video_demo_target')->nullable();

            $table->boolean('has_companion_banner')->nullable();

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
        Schema::dropIfExists('campaigns_products');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
