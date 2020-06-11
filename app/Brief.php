<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Brief extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['campaign_id',
        'client_id',
        'user_id',
        'campaign_name',
        'campaign_type',
        'start_date',
        'end_date',
        'flighting_considerations',
        'background',
        'target_audience_profile',
        'is_stack_client',
        'google_audiences',
        'file_name',
        'location',
        'ct_file_share_links',
        'ct_pixel_info'
    ];

    // set defaults
    protected $attributes = [
        'background' => null,
        'target_audience_profile' => null
    ];

    /**
     * Get the campaign that owns this brief.
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Get the files record associated with this brief.
     */
    public function briefFiles()
    {
        return $this->hasMany('App\BriefFile');
    }

    /**
     * Get the comments for this brief.
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * Get the client that owns this brief.
     */
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    /**
     * Get the user that created this brief.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Gets all the activation users belonging to this brief's client
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170428
     */
    public function getActivationUsers()
    {

        // get agency this brief belongs to
        $agency = $this->client->agency;

        // get users belonging to the agency
        $agency_users = $agency->users;

        // get activation users
        $activation_users = $agency_users
            ->where('blocked', '=', 0)
            ->where('role_id', '=', Role::ACTIVATION_USER);

        return $activation_users;
    }

    /**
     * Gets all the activation line manager users belonging to this brief's client
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170428
     */
    public function getActivationLineManagers()
    {

        // get agency this brief belongs to
        $agency = $this->client->agency;

        // get users belonging to the agency
        $agency_users = $agency->users;

        // get activation users
        $activation_users = $agency_users
            ->where('blocked', '=', 0)
            ->where('role_id', '=', Role::ACT_LINE_MANAGER);

        return $activation_users;
    }

    /**
     * Gets all the heads of activation users belonging to this brief's client
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170428
     */
    public function getHeadsOfActivationUsers()
    {

        // get agency this brief belongs to
        $agency = $this->client->agency;

        // get users belonging to the agency
        $agency_users = $agency->users;

        // get activation users
        $activation_users = $agency_users
            ->where('blocked', '=', 0)
            ->where('role_id', '=', Role::HEAD_OF_ACT);

        return $activation_users;
    }

    /**
     * Gets all the agency users belonging to this brief's client's agency
     * @deprecated see getClientUsers()
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public function getAgencyUsers()
    {

        // get agency this brief belongs to
        $agency = $this->client->agency;

        // get users belonging to the agency
        $agency_users = $agency->users;

        // get agency users
        $agency_users = $agency_users
            ->where('blocked', '=', 0)
            ->where('role_id', '=', Role::AGENCY_USER)
            ->where('last_login', '!=', null); // temp fix to prevent >50 emails being sent out in one go

        return $agency_users;
    }

    /**
     * Gets all the agency users assigned this brief's client
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170922
     */
    public function getClientUsersAttribute()
    {
        $client         = $this->client()->get()->first();

        $client_users   = $client->users()->get();

        return $client_users;
    }

    /**
     * Gets all the VOD users belonging to this brief's client's agency
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170530
     */
    public function getVodUsersAttribute()
    {
        // get agency this brief belongs to
        $agency = $this->client->agency;

        // get users belonging to the agency
        $agency_users = $agency->users;

        // get vod users
        $vod_users = $agency_users
            ->where('blocked', '=', 0)
            ->where('role_id', '=', Role::VOD_USER);

        return $vod_users;

    }

    /**
     * Gets all the briefs that need io links and have 5 days left before live date
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170504
     */
    public static function getOutstandingIoBriefs()
    {
        // get active campaigns
        $active_campaigns = Campaign::where('is_active', 1)->get();
//        var_dump(count($active_campaigns));die;

        $filtered_campaigns = new Collection();
        foreach($active_campaigns as $campaign){

            if($campaign->status->id == Status::BF_APPROVED_BY_ACT_LINE_MANAGER) {
                $campaign_start_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->start_date)));
//                var_dump($campaign_start_date->format('Y-m-d'));die;

                // date 5 days form now
                $five_days_ahead = new \DateTime("+5 day");
//                var_dump($five_days_ahead->format('Y-m-d'));die;

//                var_dump($campaign_start_date->diff($five_days_ahead));die;
//                var_dump($campaign_start_date->diff($five_days_ahead)->d);die;

                if ($campaign_start_date->diff($five_days_ahead)->d == 0){
                    $filtered_campaigns->push($campaign);
                }
            }
        }

        return $filtered_campaigns;
    }


    /**
     * Gets all the briefs that need creative tags and have 3 days left before live date
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170504
     */
    public static function getOutstandingCTBriefs()
    {
        // get active campaigns
        $active_campaigns = Campaign::where('is_active', 1)->get();
//        var_dump(count($active_campaigns));die;

        $filtered_campaigns = new Collection();
        foreach($active_campaigns as $campaign){

            if($campaign->status->id == Status::IO_UPLOADED) {
                $campaign_start_date = new \DateTime(date('Y-m-d', strtotime($campaign->brief->start_date)));
//                var_dump($campaign_start_date->format('Y-m-d'));die;

                // date 3 days form now
                $three_days_ahead = new \DateTime("+3 day");
//                var_dump($three_days_ahead->format('Y-m-d'));die;

//                var_dump($campaign_start_date->diff($five_days_ahead));die;
//                var_dump($campaign_start_date->diff($five_days_ahead)->d);die;

                if ($campaign_start_date->diff($three_days_ahead)->d == 0){
                    $filtered_campaigns->push($campaign);
                }
            }
        }

        return $filtered_campaigns;
    }

    /**
     * Gets all the briefs that need io files & dds codes and 3||5 days have elapsed after live date
     *
     * @param int $elapsed_days
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170504
     */
    public static function getOutstandingDDSCodeBriefs($elapsed_days)
    {
        // get active campaigns
        $active_campaigns = Campaign::where('is_active', 1)->get();
//        var_dump(count($active_campaigns));die;

        $filtered_campaigns = new Collection();
        foreach($active_campaigns as $campaign){

            if($campaign->status->id == Status::ADDED_IO_HOST_LINKS) {
                $campaign_start_date = new \DateTimeImmutable(date('Y-m-d', strtotime($campaign->brief->start_date)));
//                var_dump($campaign_start_date->format('Y-m-d'));die;

                // date after live date (will be 3 or 5 days)
                $elapsed_days_after_live_date = $campaign_start_date->add(new \DateInterval("P".$elapsed_days."D"));
//                var_dump($elapsed_days_after_live_date->format('Y-m-d'));die;

                if ($campaign_start_date->diff($elapsed_days_after_live_date)->d == 0){
                    $filtered_campaigns->push($campaign);
                }
            }
        }

        return $filtered_campaigns;
    }

    /**
     * Get brief's agency's email groups
     *
     * @return Collection
     *
     * @author Saeed Bhuta
     * @version 20170519
     */
    public function getAgencyEmailGroupAttribute()
    {
        // get the agency the brief belongs to
        $agency = $this->client->agency;

        switch($agency->name) {
            case 'PHD London':
                return 'adoperations@phdmedia.com';
                break;
            case 'MG OMD':
                return 'mgomdukcampaignmanagementteam@omnicommediagroup.com';
                break;
            case 'OMD':
                return 'digital.ops@omd.com';
                break;
            default:
                return '';
        }
    }

    /**
     * Get total budget depending on what stage the campaign is at in the workflow.
     * Before booking form, use figures given in briefing form. Once DSP figures have been added use these.
     *
     * @return int
     *
     * @author Saeed Bhuta
     * @version 20171031
     */
    public function getLatestTotalBudgetAttribute()
    {
        $campaign = $this->campaign;
        // get the status of the campaign

        $latest_log = $campaign->logs->sortByDesc('created_at')->first();
        $status = $latest_log->status;
        $budget_total = 0.00;

        if($status->id < Status::BOOKING_FORM_SUBMITTED){
            // go through all the products for this campaign
            if($campaign->products !== null){
                foreach ($campaign->products as $product){

                    // sum up the budget for each product
                    $budget_total = bcadd($product->pivot->budget, $budget_total, 2);
                }
            }
        }else{
            // sum up total of dsp booking figures
            $bookings = $campaign->bookingDetails;
            foreach ($bookings as $booking){
                foreach ($booking->dspBudgets as $dsp_budget){
                    $budget_total = bcadd($dsp_budget->budget, $budget_total, 2);
                }
            }
        }

        return $budget_total;
    }

    /**
     * Gets all the names of the (rich media, display or mobile) products in this brief
     *
     * @return String $products_names
     *
     * @author Saeed Bhuta
     * @version 20171016
     */
    public function getDrmProductsNamesAttribute()
    {
        $products_names = '';

        $campaign = $this->campaign;

        // get products in this brief
        if($campaign->products !== null) {
            foreach ($campaign->products as $product) {
                if($product->id == Product::DISPLAY || $product->id == Product::RICH_MEDIA || $product->id == Product::MOBILE)
                $products_names .= $product->name . ', ';
            }
        }

        return $products_names;
    }

    /**
     * Indicates whether the brief has a completed targeting grid
     *
     * @param Logs $log
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20171108
     */
    public static function hasTargetingGrid($log){
        $status = $log->status;

        if(($status->id >= Status::TARGETING_GRID_APPROVED) && (in_array($status->id, [Status::TG_REJECTED_BY_LINE_MANAGER, Status::TG_REJECTED_BY_HEAD_OF_ACTIVATION, Status::TG_REJECTED_BY_AGENCY_USER]) == false)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Indicates whether the brief has a completed booking form
     *
     * @param Logs $log
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20171109
     */
    public function hasBookingForm($log){
        $status = $log->status;

        if(($status->id >= Status::BF_APPROVED_BY_ACT_LINE_MANAGER) && (in_array($status->id, [Status::BF_REJECTED_BY_ACT_TEAM, Status::BF_REJECTED_BY_ACT_LINE_MANAGER]) == false)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Indicates whether the brief has completed DDS codes
     *
     * @param Logs $log
     *
     * @return boolean
     *
     * @author Saeed Bhuta
     * @version 20171109
     */
    public function hasDdsCodes($log){
        $status = $log->status;

        if($status->id >= Status::IO_UPLOADED){
            return true;
        }else{
            return false;
        }
    }

}
