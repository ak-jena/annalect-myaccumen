<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Campaign extends Model
{

    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_active'];

    protected $attributes = [
        'is_active' => true
    ];

    /**
     * Get the logs for this campaign.
     */
    public function logs()
    {
        return $this->hasMany('App\Log');
    }

    /**
     * Get the bookings for this campaign.
     */
    public function bookingDetails()
    {
        return $this->hasMany('App\BookingDetail');
    }

    public function getStatusAttribute()
    {
        $latest_log = $this->logs()
            ->latest()
            ->first();

        if($latest_log != null){
            $latest_log_status = $latest_log->status;
        }else{
            $latest_log_status = null;
        }


        return $latest_log_status;
    }

    public function getLastStatusBeforeCancellationAttribute()
    {
        if($this->status->id == Status::CAMPAIGN_CANCELLED){
            $logs = $this->logs()->latest()->get();


            // remove the cancelled campaign log so
            // we can see where the campaign was previously
            $first_key = $logs->keys()->first();
            $logs = $logs->forget($first_key);

            $log = $logs->first();
            $status = $log->status;

            return $status;
        }else{
            return null;
        }




        $latest_log = $this->logs()
            ->latest()
            ->first();

        if($latest_log != null){
            $latest_log_status = $latest_log->status;
        }else{
            $latest_log_status = null;
        }


        return $latest_log_status;
    }

    public function getLatestLogAttribute()
    {
        $latest_log = $this->logs()
            ->latest()
            ->first();

        return $latest_log;
    }

    /**
     * This function looks at all the booking forms for this campaigns products
     * If all have been completed then it will return a 'submitted' booking status
     * otherwise it will return 'draft' booking status
     */
    public function getAllBookingsStatus()
    {
        // set default booking form status to draft
        $booking_status = BookingStatus::find(BookingStatus::DRAFT);

        // find out number of products assigned to this campaign
        $products_count = count($this->products);

        // number of bookings for this campaign (should match product count
        // otherwise it means booking form has yet to be completed
        $bookings_count = count($this->bookingDetails);


        // check if there is a booking form for all campaigns otherwise booking is considered draft
        if($products_count !== $bookings_count){
            $booking_status = BookingStatus::find(BookingStatus::DRAFT);
        }else{
            // go through each booking
            foreach ($this->bookingDetails as $booking){
                // if booking status is draft then set status to draft and break
                if($booking->bookingStatus->id == BookingStatus::DRAFT){
                    $booking_status = BookingStatus::find(BookingStatus::DRAFT);
                    break;
                }else{ // else set status to submitted
                    $booking_status = BookingStatus::find(BookingStatus::SUBMITTED);
                }
            }
        }

        // return status
        return $booking_status;
    }

    /**
     * Get the ids of mobile,media and display products belonging to this campaign
     * in a comma separated string
     */
    public function getMediaMobileDisplayProductIds()
    {
        $products_ids = array();

        foreach ($this->products as $product){
            if($product->id == Product::RICH_MEDIA){
                $products_ids[] = Product::RICH_MEDIA;
            }elseif ($product->id == Product::MOBILE){
                $products_ids[] = Product::MOBILE;
            }elseif ($product->id == Product::DISPLAY){
                $products_ids[] = Product::DISPLAY;
            }
        }

        return implode(',',$products_ids);
    }

    /**
     * One campaign can have multiple products
     * products are re-used between campaigns
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'campaigns_products')->withPivot(
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
     * Get the brief record associated with this campaign.
     */
    public function brief()
    {
        return $this->hasOne('App\Brief');
    }

    /**
     * Get the targeting grids for this campaign.
     */
    public function grids()
    {
        return $this->hasMany('App\Grid');
    }

    /**
     * Get the creative tags for this campaign.
     */
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    /**
     * This function looks if any IO files have been uploaded for this campaign
     *
     * @return boolean
     */
    public function hasIo()
    {
        if($this->bookingDetails !== null){
            foreach ($this->bookingDetails as $booking) {
                if($booking->dspBudgets !== null){
                    foreach ($booking->dspBudgets as $dsp_budget) {
                        # code...
                        if($dsp_budget->io_file_name !== null){
                            return true;
                        }
                    }
                }
                # code...
            }
        }
        return false;
    }

    /**
     * Determines if targeting grid requires head of activation approval
     *
     * @return boolean
     */
    public function requiresHeadOfActivationApproval()
    {
        $budget_total = 0;
        $latest_log = $this->logs->sortByDesc('created_at')->first();

        // go through all the products for this campaign
        if($this->products !== null){
            foreach ($this->products as $product){

                // sum up the budget for each product
                $budget_total += $product->pivot->budget;
            }
        }

        // if budget total is over 100k and status is TG Approved by Line Manager then return true
        if($budget_total > 100000 && $latest_log->status->id == Status::TG_APPROVED_BY_LINE_MANAGER){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determines if all IO host links have been added
     * for all the bookings within this campaign
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170523
     */
    public function  getIoLinksCompleteAttribute()
    {
        // go through all the bookings for this campaign
        // then go through all the dsp budgets for each booking
        if($this->bookingDetails !== null){
            foreach ($this->bookingDetails as $booking){
                if($booking->dspBudgets !== null){
                    foreach ($booking->dspBudgets as $dsp_budget){
                        if($dsp_budget->io_host_links == null){
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Determines if all IO dds codes and io files have been added
     * for all the bookings within this campaign
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170523
     */
    public function getIoDdsCodesFilesCompleteAttribute()
    {
        // go through all the bookings for this campaign
        // then go through all the dsp budgets for each booking
        if($this->bookingDetails !== null){
            foreach ($this->bookingDetails as $booking){
                if($booking->dspBudgets !== null) {
                    foreach ($booking->dspBudgets as $dsp_budget) {
                        if ($dsp_budget->dds_code == null) {
                            return false;
                        }
                        if ($dsp_budget->io_file_name == null) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Determines if total budget is >100k
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20170428
     */
    public function isOver100k()
    {
        $budget_total = 0;

        // go through all the products for this campaign
        if($this->products !== null){
            foreach ($this->products as $product){

                // sum up the budget for each product
                $budget_total += $product->pivot->budget;
            }
        }

        if($budget_total > 100000){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get total budget for this brief
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170601
     */
    public function getTotalBudgetAttribute()
    {
        $budget_total = 0.00;

        // go through all the products for this campaign
        if($this->products !== null){
            foreach ($this->products as $product){

                // sum up the budget for each product
                $budget_total = bcadd($product->pivot->budget, $budget_total, 2);
            }
        }

        return $budget_total;
    }

    /**
     * Gets all the campaigns that are ready to go live
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170711
     */
    public static function getLiveEligibleCampaigns()
    {
        // get active campaigns
        $active_campaigns = Campaign::where('is_active', 1)->get();
//        var_dump(count($active_campaigns));die;

        $filtered_campaigns = new Collection();
        foreach($active_campaigns as $campaign){

            if(in_array($campaign->status->id, array(Status::IO_UPLOADED, Status::UPLOADED_CREATIVE_TAGS))) {
                $campaign_start_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->start_date)));
                $campaign_end_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->end_date)));
//                var_dump($campaign_start_date->format('Y-m-d'));die;

                // todays date
                $today = new \DateTime();

                // if campaign date is today or in the past
                if ($campaign_start_date->diff($today)->d == 0 || $campaign_start_date < $today){
                    if($campaign_end_date > $today){
                        $filtered_campaigns->push($campaign);
                    }
                }
            }
        }

        return $filtered_campaigns;
    }

    /**
     * Gets all the campaigns that are ready to be marked as complete
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20180222
     */
    public static function getCompletedEligibleCampaigns()
    {
        // get active campaigns
        $active_campaigns = Campaign::where('is_active', 1)->get();
//        var_dump(count($active_campaigns));die;

        $filtered_campaigns = new Collection();
        foreach($active_campaigns as $campaign){

            if(in_array($campaign->status->id, array(Status::CAMPAIGN_LIVE, Status::IO_UPLOADED, Status::UPLOADED_CREATIVE_TAGS))) {
                $campaign_start_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->start_date)));
                $campaign_end_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->end_date)));
//                var_dump($campaign_start_date->format('Y-m-d'));die;

                // todays date
                $today = new \DateTime();

                // if today does not fall into the range then campaign will be marked as complete
                if(!$today >= $campaign_start_date && $today <= $campaign_end_date){
                    $filtered_campaigns->push($campaign);
                }

                // if campaign end date is today or in the past
//                if ($campaign_end_date->diff($today)->d == 0 ||  $today > $campaign_end_date){
//                    $filtered_campaigns->push($campaign);
//                }
            }
        }

        return $filtered_campaigns;
    }


    /**
     * Get the json targeting grids for this campaign.
     */
    public function targetingGrids()
    {
        return $this->hasMany('App\TargetingGrid');
    }
}
