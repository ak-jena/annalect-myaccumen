<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pricing_model',
        'has_budget_silos',
        'budget_silos',
        'budget_silos_total',
        'cpm_advice_type',
        'cpm_advice_value',
        'serving_costs',
        'targeting_requirements',
        'has_onsite_tracking_pixel',
        'tracking_pixel_details',
        'tracking_pixel_events',
        'tracking_tag',
        'is_rich_media',
        'rm_creative_format',
        'rm_creative_format_other',
        'rm_creative_notes',
        'is_1x1_supplied',
        'supplied_creative_formats',
        'specific_activity_tags',
        'is_reporting',
        'weekly_updates',
        'metrics_required',
        'adserver',
        'adserver_metric',
        'site_list',
        'audience_segment_examples',
        'other_info',
        'omg_programmatic_assessment',
        'requested_tracking_pixels',
        'implemented_pixels',
        'data_collection_code',
        'tracking_tag_dsp',
        '1x1_adserver_trackers',
        'reporting_description'
    ];

    /**
     * Get the Campaign that this booking belongs to
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Product this booking is for
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    /**
     * Get the dsp_budgets belonging to this booking
     */
    public function dspBudgets()
    {
        return $this->hasMany('App\DspBudget', 'booking_id');
    }

    /**
     * Status of this Booking
     */
    public function bookingStatus()
    {
        return $this->belongsTo('App\BookingStatus');
    }

}
