<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // correspond to IDs in the DB table
    const DISPLAY = 1;
    const RICH_MEDIA = 2;
    const MOBILE = 3;
    const AUDIO = 4;
    const VOD = 5;

    //
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * One campaign can have multiple products
     * products are re-used between campaigns
     */
    public function campaigns()
    {
        return $this->belongsToMany('App\Campaign', 'campaigns_products')->withPivot(
            'budget',
            'campaign_objective',
            'primary_metric',
            'primary_metric_goal_value',
            'metric_2',
            'metric_2_goal_value',
            'metric_3',
            'metric_3_goal_value',
            'metric_4',
            'metric_4_goal_value',
            'display_media_mobile_activity_1',
            'display_media_mobile_metric_1',
            'display_media_mobile_value_1',
            'display_media_mobile_activity_2',
            'display_media_mobile_metric_2',
            'display_media_mobile_value_2',
            'display_media_mobile_activity_3',
            'display_media_mobile_metric_3',
            'display_media_mobile_value_3',
            'geo_targeting',
            'geo_targeting_details',
            'inventory_screentypes',
            'specific_activity_response',
            'contextual_env_pp_response',
            'creative_lengths',
            'creative_type',
            'interactive_creative_provider',
            'video_creative_type',
            'video_demo_target',
            'has_companion_banner'
        )->withTimestamps();
    }


    /**
     * DSPs belong to products. They can be shared between products, hence many to many
     */
    public function dsps()
    {
        return $this->belongsToMany('App\Dsp', 'dsps_products');
    }

    /**
     * returns dsp budgets for this product
     */
    public function dspBudgets()
    {
        return $this->hasMany('App\DspBudget');
    }

    /**
     * returns booking for this product
     */
    public function bookingDetails()
    {
        return $this->hasMany('App\BookingDetail');
    }

    public function targetingGrids()
    {
        return $this->hasMany('App\TargetingGrid');
    }
}

