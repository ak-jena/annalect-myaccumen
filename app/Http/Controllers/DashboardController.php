<?php

namespace App\Http\Controllers;

use App\Brief;
use App\Campaign;
use App\Client;
//use App\Http\Requests\Request;
use App\Status;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function filterDashboard(){
        return view('dashboard.main-dashboard');
    }

    public function liveDashboard(){
        return view('dashboard.live-dashboard');
    }

    public function completedDashboard(){
        return view('dashboard.completed-dashboard');
    }

    public function retrieveCampaigns(Request $request){

        $filters        = $request->toArray();
        $client_id      = $filters['client_id'];
        $client_ids     = array();
        $product_id     = $filters['product_id'];
        $date_range     = $filters['date_range'];

        $dates_array    = explode('to', $date_range);

        if($dates_array[0] == '') {
            $dates_array = null;
        }else{
            $start_date     = trim($dates_array[0]);
            $start_date = date('Y-m-d', strtotime($start_date));

            if (array_key_exists(1, $dates_array)){
                $end_date   = trim($dates_array[1]);
                $end_date   = date('Y-m-d', strtotime($end_date));
            }else{
                $end_date = date('Y-m-d', strtotime('+1 day', $start_date));
            }
        }

        if($client_id == 0){
            $client_id = null;
        }

        if($product_id == 0){
            $product_id = null;
        }

        $briefs = Brief::with([
            'client',
            'campaign.logs.status',
            'campaign.products',
        ])
        ->withCount('comments')
        ->whereHas('campaign', function($query) {
            $query->where('is_active', '=', 1);
        })->get();

        // client restriction/filter
        if($client_id !== null){
            $briefs = $briefs->where('client_id', $client_id);
        }else{
            // enforce default client restriction

            if(\Baselib::isAgencyUser() || \Baselib::isActivationUser() || \Baselib::isVodUser() || \Baselib::isActivationLineManager()){
                // restrict agency and activation users to only seeing campaigns of advertisers/clients belonging to their agency
                $logged_in_user = \Baselib::getUser(\Auth::user()->id);

                $clients = $logged_in_user->permittedClients;

                foreach($clients as $client){
                    $client_ids[] = $client->id;
                }

                $briefs = $briefs->whereIn('client_id', $client_ids);
            }else{
                // no client restriction
                $clients = null;
            }
        }

        // products filter todo
        if($product_id !== null){
            $briefs = $briefs->filter(function($brief) use ($product_id) {
                $products = $brief->campaign->products;
                $product_ids = [];

                foreach ($products as $product){
                    $product_ids[] = $product->id;
                }
                return (in_array($product_id, $product_ids));
            });
        }

        // date range filter
        if($dates_array !== null){
            $briefs = $briefs->filter(function($brief) use ($start_date, $end_date) {
                return ($brief->start_date > $start_date) && ($brief->start_date < $end_date);
            });
        }

        // sort according to dashboard column
        // sort campaigns according to section
        $new_brief_statuses = [
            'New Brief in Progress',
        ];

        $new_brief_campaigns = $briefs->filter(function($brief) use ($new_brief_statuses) {
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if(in_array($latest_log->status->name, $new_brief_statuses)){
                return $brief;
            }
        });

        $targeting_grid_statuses = [
            'Brief Submitted',
            'Targeting Grid Uploaded',
            'TG Approved by Line Manager',
            'TG Approved by Head of Activation',
            'TG Rejected by Line Manager',
            'TG Rejected by Head of Activation',
            'TG Rejected by Agency User'
        ];

        $targeting_grid_campaigns = $briefs->filter(function($brief) use ($targeting_grid_statuses) {
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if(in_array($latest_log->status->name, $targeting_grid_statuses)){
                return $brief;
            }
        });

        $booking_form_statuses = [
            'Targeting Grid Approved',
            'Booking Form Submitted',
            'BF Approved by Act. Team',
            'BF Rejected by Act. Team',
            'BF Rejected by Act. Line Manager'

        ];

        $booking_form_campaigns = $briefs->filter(function($brief) use ($booking_form_statuses) {
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if(in_array($latest_log->status->name, $booking_form_statuses)){
                return $brief;
            }
        });

        $io_campaigns = $briefs->filter(function($brief){
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if(in_array($latest_log->status->name, array('BF Approved by Act. Line Manager', 'Host Links added by Agency User'))){
                return $brief;
            }
        });

        $creative_tags_campaigns = $briefs->filter(function($brief){
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if(in_array($latest_log->status->name,  array('IO Uploaded', 'Uploaded Creative Tags'))){
                return $brief;
            }
        });

        return view('dashboard.campaign-tiles', ['new_brief_campaigns' => $new_brief_campaigns,
            'targeting_grid_campaigns' => $targeting_grid_campaigns,
            'booking_form_campaigns' => $booking_form_campaigns,
            'io_campaigns' => $io_campaigns,
            'creative_tags_campaigns' => $creative_tags_campaigns,]);
    }

    public function retrieveLiveCampaigns(Request $request){

        $filters        = $request->toArray();
        $client_id      = $filters['client_id'];
        $client_ids     = array();
        $product_id     = $filters['product_id'];
        $date_range     = $filters['date_range'];

        $dates_array    = explode('to', $date_range);

        if($dates_array[0] == '') {
            $dates_array = null;
        }else{
            $start_date     = trim($dates_array[0]);
            $start_date = date('Y-m-d', strtotime($start_date));

            if (array_key_exists(1, $dates_array)){
                $end_date   = trim($dates_array[1]);
                $end_date   = date('Y-m-d', strtotime($end_date));
            }else{
                $end_date = date('Y-m-d', strtotime('+1 day', $start_date));
            }
        }

        if($client_id == 0){
            $client_id = null;
        }

        if($product_id == 0){
            $product_id = null;
        }

        $briefs = Brief::with([
            'client',
            'campaign.logs.status',
            'campaign.products',
        ])
        ->withCount('comments')
        ->whereHas('campaign', function($query) {
            $query->where('is_active', '=', 1);
        })->get();

        // client restriction/filter
        if($client_id !== null){
            $briefs = $briefs->where('client_id', $client_id);
        }else{
            // enforce default client restriction

            if(\Baselib::isAgencyUser() || \Baselib::isActivationUser() || \Baselib::isVodUser() || \Baselib::isActivationLineManager()){
                // restrict agency and activation users to only seeing campaigns of advertisers/clients belonging to their agency
                $logged_in_user = \Baselib::getUser(\Auth::user()->id);

                $clients = $logged_in_user->permittedClients;

                foreach($clients as $client){
                    $client_ids[] = $client->id;
                }

                $briefs = $briefs->whereIn('client_id', $client_ids);
            }else{
                // no client restriction
                $clients = null;
            }
        }

        if($product_id !== null){
            $briefs = $briefs->filter(function($brief) use ($product_id) {
                $products = $brief->campaign->products;
                $product_ids = [];

                foreach ($products as $product){
                    $product_ids[] = $product->id;
                }
                return (in_array($product_id, $product_ids));
            });
        }

        // date range filter
        if($dates_array !== null){
            $briefs = $briefs->filter(function($brief) use ($start_date, $end_date) {
                return ($brief->start_date > $start_date) && ($brief->start_date < $end_date);
            });
        }


        $live_status_id = Status::CAMPAIGN_LIVE;
        $live_briefs    = $briefs->filter(function($brief) use ($live_status_id) {
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if ($latest_log->status->id == $live_status_id) {
                return $brief;
            }
        });

        return view('dashboard.live-campaigns-dashboard', [
            'live_briefs' => $live_briefs
        ]);
    }

    public function retrieveCompletedCampaigns(Request $request){

        $filters        = $request->toArray();
        $client_id      = $filters['client_id'];
        $client_ids     = array();
        $product_id     = $filters['product_id'];
        $date_range     = $filters['date_range'];

        $dates_array    = explode('to', $date_range);

        if($dates_array[0] == '') {
            $dates_array = null;
        }else{
            $start_date     = trim($dates_array[0]);
            $start_date = date('Y-m-d', strtotime($start_date));

            if (array_key_exists(1, $dates_array)){
                $end_date   = trim($dates_array[1]);
                $end_date   = date('Y-m-d', strtotime($end_date));
            }else{
                $end_date = date('Y-m-d', strtotime('+1 day', $start_date));
            }
        }

        if($client_id == 0){
            $client_id = null;
        }

        if($product_id == 0){
            $product_id = null;
        }

        $briefs = Brief::with([
            'client',
            'campaign.logs.status',
            'campaign.products',
        ])
            ->withCount('comments')
            ->whereHas('campaign', function($query) {
                $query->where('is_active', '=', 1);
            })->get();

        // client restriction/filter
        if($client_id !== null){
            $briefs = $briefs->where('client_id', $client_id);
        }else{
            // enforce default client restriction

            if(\Baselib::isAgencyUser() || \Baselib::isActivationUser() || \Baselib::isVodUser() || \Baselib::isActivationLineManager()){
                // restrict agency and activation users to only seeing campaigns of advertisers/clients belonging to their agency
                $logged_in_user = \Baselib::getUser(\Auth::user()->id);

                $clients = $logged_in_user->permittedClients;

                foreach($clients as $client){
                    $client_ids[] = $client->id;
                }

                $briefs = $briefs->whereIn('client_id', $client_ids);
            }else{
                // no client restriction
                $clients = null;
            }
        }

        if($product_id !== null){
            $briefs = $briefs->filter(function($brief) use ($product_id) {
                $products = $brief->campaign->products;
                $product_ids = [];

                foreach ($products as $product){
                    $product_ids[] = $product->id;
                }
                return (in_array($product_id, $product_ids));
            });
        }

        // date range filter
        if($dates_array !== null){
            $briefs = $briefs->filter(function($brief) use ($start_date, $end_date) {
                return ($brief->start_date > $start_date) && ($brief->start_date < $end_date);
            });
        }


        $completed_status_id = Status::CAMPAIGN_COMPLETED;
        $completed_briefs    = $briefs->filter(function($brief) use ($completed_status_id) {
            $latest_log = $brief->campaign->logs->sortByDesc('created_at')->first();

            if ($latest_log->status->id == $completed_status_id) {
                return $brief;
            }
        });

        return view('dashboard.completed-campaigns-dashboard', [
            'completed_briefs' => $completed_briefs
        ]);
    }

    public function cancelledCampaigns(){
        $cancelled_campaigns = new Collection();

        // get the clients the user is allowed to see (agency and non developer users)
        if(\Baselib::isAgencyUser() || \Baselib::isActivationUser() || \Baselib::isVodUser() || \Baselib::isActivationLineManager()){
            $logged_in_user = \Baselib::getUser(\Auth::user()->id);

            $clients = $logged_in_user->permittedClients;

            foreach ($clients as $client) {
                foreach ($client->briefs as $brief) {
                    if ($brief->campaign->status !== null) {
                        if ($brief->campaign->status->id == Status::CAMPAIGN_CANCELLED) {
                            $cancelled_campaigns->push($brief->campaign);
                        }
                    }

                }
            }
        }else{
            // get all campaigns
            $all_campaigns = Campaign::where('is_active', 0)->get();
            foreach ($all_campaigns as $campaign){
                $campaign_status = $campaign->status;
                if($campaign_status != null){
                    if($campaign_status->id == Status::CAMPAIGN_CANCELLED){
                        $cancelled_campaigns->push($campaign);
                    }
                }

            }
        }

        // sort campaigns according to section
        $new_brief_statuses = [
            'New Brief in Progress',
        ];

        $new_brief_campaigns = $cancelled_campaigns->filter(function($campaign) use ($new_brief_statuses) {
            if(in_array($campaign->lastStatusBeforeCancellation->name, $new_brief_statuses) && $campaign->brief !== null){
                return $campaign;
            }
        });

        $targeting_grid_statuses = [
            'Brief Submitted',
            'Targeting Grid Uploaded',
            'TG Approved by Line Manager',
            'TG Approved by Head of Activation',
            'TG Rejected by Line Manager',
            'TG Rejected by Head of Activation',
            'TG Rejected by Agency User'
        ];

        $targeting_grid_campaigns = $cancelled_campaigns->filter(function($campaign) use ($targeting_grid_statuses) {
            if(in_array($campaign->lastStatusBeforeCancellation->name, $targeting_grid_statuses) && $campaign->brief !== null){
                return $campaign;
            }
        });

        $booking_form_statuses = [
            'Targeting Grid Approved',
            'Booking Form Submitted',
            'BF Approved by Act. Team',
            'BF Rejected by Act. Team',
            'BF Rejected by Act. Line Manager'

        ];

        $booking_form_campaigns = $cancelled_campaigns->filter(function($campaign) use ($booking_form_statuses) {
            if(in_array($campaign->lastStatusBeforeCancellation->name, $booking_form_statuses) && $campaign->brief !== null){
                return $campaign;
            }
        });

        $io_campaigns = $cancelled_campaigns->filter(function($campaign){
            if(in_array($campaign->lastStatusBeforeCancellation->name, array('BF Approved by Act. Line Manager', 'Host Links added by Agency User',)) && $campaign->brief !== null){
                return $campaign;
            }
        });

        $creative_tags_campaigns = $cancelled_campaigns->filter(function($campaign){
            if(in_array($campaign->lastStatusBeforeCancellation->name, array('IO Uploaded', 'Uploaded Creative Tags')) && $campaign->brief !== null){
                return $campaign;
            }
        });

        return view('dashboard.cancelled-campaigns-dashboard', ['new_brief_campaigns' => $new_brief_campaigns,
            'targeting_grid_campaigns' => $targeting_grid_campaigns,
            'booking_form_campaigns' => $booking_form_campaigns,
            'io_campaigns' => $io_campaigns,
            'creative_tags_campaigns' => $creative_tags_campaigns,
        ]);

    }
}