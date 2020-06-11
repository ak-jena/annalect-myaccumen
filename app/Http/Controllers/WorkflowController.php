<?php
/**
 * Created by PhpStorm.
 * User: saeed.bhuta
 * Date: 28/11/2016
 * Time: 11:09
 */

namespace App\Http\Controllers;

use App\BookingDetail;
use App\BookingStatus;
use App\Brief;
use App\Comment;
use App\Dsp;
use App\DspBudget;
use App\Http\Requests\ProcessTags;
use App\Log;
use App\Mail\ActUploadedIos;
use App\Mail\AgencyUploadedTags;
use App\Mail\ALMApprovedBF;
use App\Mail\ALMRejectedBF;
use App\Mail\ALMTGApprovalConfirmation;
use App\Mail\ATApprovedBF;
use App\Mail\ATRejectedBF;
use App\Mail\BriefSubmitted;
use App\Mail\BriefUpdated;
use App\Mail\TgApprovedByAgency;
use App\Mail\TgApprovedByALM;
use App\Mail\TgApprovedByHoA;
use App\Mail\TgUploaded;
use App\Mail\UpperTgApprovedByALM;
use App\Mail\ALMApprovedBFAgeConf;
use App\Product;
use App\Section;
use App\Status;
use App\Campaign;

use App\Http\Requests\ProcessAudio1;
use App\Http\Requests\ProcessAudio2;
use App\Http\Requests\ProcessCampaignInfo1;
use App\Http\Requests\ProcessCampaignInfo2;
use App\Http\Requests\ProcessDisplayMediaMobile1;
use App\Http\Requests\ProcessVideo1;
use App\Http\Requests\ProcessVideo2;
use App\Http\Requests\ProcessFileUpload;
use App\Http\Requests\ProcessGrid;

use App\Repositories\CommentRepository;

use App\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use DB;
use Alert;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WorkflowController extends Controller
{
    /**
     * Show workflow form page
     *
     * @param  int  $campaign_id
     * @param  int  $existing_brief_id
     * @return Response
     */
    public function showWorkflowForm($campaign_id = null, $existing_brief_id = null)
    {
        $campaign                   = null;
        $booking_details            = null;
        $active_section             = array(1); // default
        $existing_campaign_names    = [];

        $vod_dsps   = new Collection();
        $vod        = Product::find(Product::VOD);
        $vod_dsps   = $vod->dsps;

        $campaign_id        = ($campaign_id == '0') ? null : $campaign_id;
        $existing_brief_id  = ($existing_brief_id == '0') ? null : $existing_brief_id;
//        dd($existing_brief_id);

        $duplicate_brief    = null;
        $duplicate_brief_id = null;
        $duplicate_products = null;

        // determine if existing campaign
        if($campaign_id != null){

            $campaign = Campaign::where('id', $campaign_id)->first();
            // restrict agency users to only seeing campaigns of advertisers/clients belonging to their agency
            if(\Baselib::isAgencyUser() || \Baselib::isVodUser() || \Baselib::isActivationUser() || \Baselib::isActivationLineManager()){
                $logged_in_user = \Baselib::getUser(\Auth::user()->id);

                $users_clients = $logged_in_user->permittedClients;

                $campaign_client = $campaign->brief->client;

                if($users_clients->contains('id', $campaign_client->id) == false){
                    // redirect to dashboard
                    return redirect()->route('dashboard');
                }
            }

            if(count($campaign->bookingDetails) > 0){
                $booking_details = $campaign->bookingDetails;
            }

            // determine which section/panel the form 
            // should display - depending on campaign status
            $campaign_status_id = $campaign->status->id;

            if(in_array($campaign_status_id, array(1))){
                $active_section = array(1);
            }elseif (in_array($campaign_status_id, array(2, 3, 4, 5, 6, 7, 8))) { // tg
                $active_section = array(2);
            }elseif (in_array($campaign_status_id, array(9, 10, 11, 12, 13))) { // bf
                $active_section = array(3);
            }elseif (in_array($campaign_status_id, array(14, 15, 16))) { // IO
                $active_section = array(4,5);
            }
        }else{
            $user   = User::findorFail(\Auth::id());
            $users_agency_id = $user->agencies()->pluck('id');

            // get existing campaign names (to populate existing campaign select
            $campaign_names_result = DB::table('briefs')
                ->join('clients', 'briefs.client_id', '=', 'clients.id')
                ->join('agencies', 'clients.agency_id', '=', 'agencies.id')
                ->join('campaigns', 'briefs.campaign_id', '=', 'campaigns.id')
                ->join('logs', 'logs.campaign_id', '=', 'campaigns.id')
                ->join('statuses', 'logs.status_id', '=', 'statuses.id')
                ->select(DB::raw('briefs.id, CONCAT(clients.name," - ",briefs.campaign_name) AS campaign_name, max(logs.created_at)'))
                ->groupBy('briefs.id')
                ->whereIn('agencies.id', $users_agency_id->toArray())
                ->whereIn('logs.status_id', [10.11,12,13,14,15,16,17,19,20] )
                ->get()->toArray();

            // flatten array
            foreach ($campaign_names_result as $database_record){
                $existing_campaign_names[$database_record->id] = $database_record->campaign_name;
            }

            // else check if an existing campaign was selected and retrieve its details to populate the brief
            if($existing_brief_id !== null){
                $brief = Brief::find($existing_brief_id);
                $duplicate_brief_id = $brief->id;
                $duplicate_brief = $brief->replicate();

                $duplicate_products = $duplicate_brief->campaign->products()->orderBy('name','asc')->pluck('id', 'name');

            }
        }

        // get sections
        $sections = Section::orderBy('level', 'asc')->get();

        return view('workflow.form', ['sections' => $sections, 'campaign' => $campaign, 'booking_details' => $booking_details, 'active_section' => $active_section, 'vod_dsps' => $vod_dsps, 'existing_campaign_names' => $existing_campaign_names, 'duplicate_brief_id' => $duplicate_brief_id, 'duplicate_brief' => $duplicate_brief, 'duplicate_products' => $duplicate_products]);
    }

    /**
     * Process brief submission campaign info pt 1 (AJAX)
     *
     * @param Request $request
     * @param CommentRepository $comment_repository
     */
    public function processKeyCampaignInfo1(Request $request, CommentRepository $comment_repository)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();
        // determine if add or edit
        $operation_type = $input['operation_type'];

        $campaign_name_validation_rule = 'required|max:150|unique:briefs';

        if($operation_type == 'existing'){
            $campaign_id = $input['campaign_id'];
            $campaign = Campaign::findOrFail($campaign_id);
            $brief = $campaign->brief;

            $campaign_name_validation_rule = 'required||max:150|unique:briefs,campaign_name,'.$brief->id;
        }

        $this->validate($request, [
            //
            'product' => 'required',
            'client_id' => 'required',
            'user_id' => 'required',
            'campaign_name' => $campaign_name_validation_rule,
            'campaign_type' => 'required',
            'campaign_dates' => 'required',
            'flighting_considerations' => 'required'
        ]);

        // format/prepare data to save later
        $input['campaign_dates'] = explode(' - ', $input['campaign_dates']);
        $input['start_date'] = date('Y-m-d', strtotime($input['campaign_dates'][0]));
        $input['end_date'] = date('Y-m-d', strtotime($input['campaign_dates'][1]));

        // determine if add or edit
        $operation_type = $input['operation_type'];


        if($operation_type == 'new'){
            // remove the campaign id from the form data (only required for existing campaigns)
            unset($input['campaign_id']);

            // create new campaign
            $campaign = new Campaign();
            // create new brief
            $brief = new Brief();

            $message = 'New Campaign and Brief created successfully.';
            // status
            $status = Status::where('name', 'New Brief in Progress')->first();
        }elseif($operation_type == 'existing'){
            $campaign_id = $input['campaign_id'];
            $campaign = Campaign::findOrFail($campaign_id);
            $brief = $campaign->brief;

            $message = 'Campaign and Brief updated successfully.';

            // post brief updated comment
            $logged_in_user = \Baselib::getUser(\Auth::user()->id);
            $system_comment_user = User::where('name', 'System Message')->first();
            $comment_repository->create(['title' => 'Brief updated', 'body' => '<p>Brief was updated by <strong>'.$logged_in_user->name.'.</strong></p>', 'author_id' => $system_comment_user->id, 'brief_id' => $brief->id]);
        }

        $status = $this->getNextStatus($campaign);

        DB::transaction(function () use ($campaign, $brief, $status, $input) {
            $campaign->save();
            // assign selected product(s) to campaign
            $campaign->products()->sync($input['product']);

            $brief->campaign()->associate($campaign);
            $brief->fill($input)->save();

            // write status to log table
            if($status !== null){
                $log = new Log();
                $log->status()->associate($status);
                $log->campaign()->associate($campaign);

                $user = User::findorFail(\Auth::id());
                $log->user()->associate($user);

                $log->save();
            }

        });

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => $message,
            'campaign_id' => $campaign->id
        ]);
    }

    /**
     * Process brief submission campaign info pt 2 (AJAX)
     *
     * @param ProcessCampaignInfo2 $request
     * @param CommentRepository $comment_repository
     */
    public function processKeyCampaignInfo2(ProcessCampaignInfo2 $request, CommentRepository $comment_repository)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign and brief
        $campaign_id = $input['campaign_id'];
        $campaign = Campaign::find($campaign_id);

        $brief = $campaign->brief;

        // update brief
        $brief->background = $input['background'];
        $brief->target_audience_profile = $input['target_audience_profile'];
        $brief->save();

        // update product budgets
        $products = $brief->campaign->products;
        foreach ($products as $product){
            $budget_value = $input[str_replace(' ','_',strtolower($product->name).'_budget')];
            $campaign->products()->updateExistingPivot($product->id, ['budget' => $budget_value]);
        }

        // if vod product selected then get dsp budgets
        if($products->contains('id', Product::VOD)){
            // if unsure does not exist in input array
            if(array_key_exists('vod_dsp_unsure', $input) == false){

                $vod_product = $product->where('id', Product::VOD)->first();

                // check if booking_detail exists for the campaign and given product
                $booking_detail = BookingDetail::where([
                    ['campaign_id', $campaign->id],
                    ['product_id', $vod_product->id]
                ])->first();

//            print('<pre>'.var_dump($booking_detail).'</pre>');die;

                // retrieve if exists
                if($booking_detail !== null){
                    // existing budgets for the booking
                    $booking_dsp_budgets = $booking_detail->dspBudgets;
                }else{
//                  else create new booking_detail record
                    $booking_detail = new BookingDetail();

                    // set default status to draft
                    $booking_status = BookingStatus::find(BookingStatus::DRAFT);
                    $booking_detail->bookingStatus()->associate($booking_status);

                    $booking_detail->product()->associate($vod_product);
                    $booking_detail->campaign()->associate($campaign);
                    $booking_detail->save();

                    $booking_dsp_budgets = array();
                }

                // clear previous budgets
                foreach ($booking_dsp_budgets as $existing_dsp_budget){
                    $existing_dsp_budget->delete();
                }

                // retrieve dsps budget data from the form for the current product
                $dsps_budgets = $input['vod_dsp'];



                // save dsps and budget that are > 0
                foreach ($dsps_budgets as $dsp_id => $budget){
                    if($budget > 0){
                        $dsp    = Dsp::findOrFail($dsp_id);
                        $user   = User::findorFail(\Auth::id());

                        // insert new dsp_budget record(s)
                        $dsp_budget             = new DspBudget();
                        $dsp_budget->booking()->associate($booking_detail);
                        $dsp_budget->dsp()->associate($dsp);
                        $dsp_budget->user()->associate($user);
                        $dsp_budget->budget     = $budget;
                        $dsp_budget->save();

//                    $updated_products[] = $formatted_product_name;
                    }

                }
            }
        }

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
            // post brief updated comment
//            $logged_in_user = \Baselib::getUser(\Auth::user()->id);
//            $system_comment_user = User::where('name', 'System Message')->first();
//            $comment_repository->create(['title' => 'Brief updated', 'body' => '<p>Brief was updated by <strong>'.$logged_in_user->name.'.</strong></p>', 'author_id' => $system_comment_user->id, 'brief_id' => $brief->id]);
        }

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully updated campaign with budget data'
        ]);
    }

    /**
     * Process brief submission display-media-mobile-1 (AJAX)
     */
    public function processDisplayMediaMobile1(ProcessDisplayMediaMobile1 $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // identify how many products were selected
        $product_ids = explode(',',$input['products_ids']);

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];
        $campaign = Campaign::find($campaign_id);

        $campaign_objective         = $input['campaign_objective'];
        $primary_metric             = $input['primary_campaign_metric'];
        $primary_metric_goal_value  = $input['metric_goal_value'];

        $activity_1             = $input['activity_1'];
        $activity_1_metric      = $input['activity_1_metric'];
        $activity_1_goal_value  = $input['activity_1_goal_value'];

        // create a campaigns_product record for each selected product
        foreach ($product_ids as $product_id){
            $campaign->products()->updateExistingPivot($product_id, [
                'campaign_objective'                => $campaign_objective,
                'primary_metric'                    => $primary_metric,
                'primary_metric_goal_value'         => $primary_metric_goal_value,
                'display_media_mobile_activity_1'   => $activity_1,
                'display_media_mobile_metric_1'     => $activity_1_metric,
                'display_media_mobile_value_1'      => $activity_1_goal_value,
            ]);
        }

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign
        return response()->json([
            'message' => 'processed DisplayMediaMobile1!'
        ]);
    }

    /**
     * Process brief submission display-media-mobile-2 (AJAX)
     */
    public function processDisplayMediaMobile2(Request $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // identify how many products were selected
        $product_ids = explode(',',$input['products_ids']);

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];
        $campaign = Campaign::find($campaign_id);

        $activity_2             = $input['activity_2'];
        $activity_2_metric      = $input['activity_2_metric'];
        $activity_2_goal_value  = $input['activity_2_goal_value'];

        $activity_3             = $input['activity_3'];
        $activity_3_metric      = $input['activity_3_metric'];
        $activity_3_goal_value  = $input['activity_3_goal_value'];

        // create a campaigns_product record for each selected product
        foreach ($product_ids as $product_id){
            $campaign->products()->updateExistingPivot($product_id, [
                'display_media_mobile_activity_2'   => $activity_2,
                'display_media_mobile_metric_2'     => $activity_2_metric,
                'display_media_mobile_value_2'      => $activity_2_goal_value,
                'display_media_mobile_activity_3'   => $activity_3,
                'display_media_mobile_metric_3'     => $activity_3_metric,
                'display_media_mobile_value_3'      => $activity_3_goal_value,
            ]);
        }

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }
        return response()->json([
            'message' => 'processed DisplayMediaMobile2!'
        ]);
    }

    /**
     * Process brief submission display-media-mobile-3 (AJAX)
     */
    public function processDisplayMediaMobile3(Request $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();
//        dump($input);

        // identify how many products were selected
        $product_ids = explode(',',$input['products_ids']);

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];

        $campaign = Campaign::find($campaign_id);

        $geo_targeting             = $input['geo_targeting'];
        $geo_targeting_details     = $input['geo_targeting_details'];

        if(array_key_exists('inventory_screentypes', $input)) {
            $selected_inventory_screentypes = $input['inventory_screentypes'];
        }else{
            $selected_inventory_screentypes = array();
        }

//        dump($selected_inventory_screentypes);

        $inventory_screentypes = array(
            'Desktop' => 'N',
            'Tablet' => 'N',
            'Mobile Web' => 'N',
            'Mobile App' => 'N',
            'Native' => 'N'
        );

        foreach ($selected_inventory_screentypes as $screentype){
            $inventory_screentypes[$screentype] = 'Y';
        }

//        dump($inventory_screentypes);

        $specific_activity_response = $input['specific_activity'];
        $env_pp_response = $input['partners_response'];


        // create a campaigns_product record for each selected product
        foreach ($product_ids as $product_id){
            $campaign->products()->updateExistingPivot($product_id, [
                'geo_targeting'                 => $geo_targeting,
                'geo_targeting_details'         => $geo_targeting_details,
                'inventory_screentypes'         => json_encode($inventory_screentypes),
                'specific_activity_response'    => $specific_activity_response,
                'contextual_env_pp_response'    => $env_pp_response,
            ]);
        }

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        return response()->json([
            'message' => 'processed DisplayMediaMobile3!'
        ]);
    }

    /**
     * Process brief submission audio-1 (AJAX)
     */
    public function processAudio1(ProcessAudio1 $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];

        $campaign_objective         = $input['audio_campaign_objective'];
        $primary_metric             = $input['audio_primary_campaign_metric'];
        $primary_metric_goal_value  = $input['audio_metric_goal_value'];
        $geo_targeting              = $input['audio_geo_targeting'];
        $geo_targeting_details      = $input['audio_geo_targeting_details'];

        $has_companion_banner       = null; // default value if nothing selected

        if(array_key_exists('audio_has_companion_banner', $input)){
            if($input['audio_has_companion_banner'] !== ''){
                if($input['audio_has_companion_banner'] == 0 || $input['audio_has_companion_banner'] == 1){
                    $has_companion_banner       = $input['audio_has_companion_banner'];
                }
            }
        }



        $campaign = Campaign::find($campaign_id);

        $campaign->products()->updateExistingPivot($input['product_id'], [
            'campaign_objective' => $campaign_objective,
            'primary_metric' => $primary_metric,
            'primary_metric_goal_value' => $primary_metric_goal_value,
            'geo_targeting' => $geo_targeting,
            'geo_targeting_details' => $geo_targeting_details,
            'has_companion_banner' => $has_companion_banner
        ]);

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully updated campaign with audio product data'
        ]);
    }

    /**
     * Process brief submission audio-2 (AJAX)
     */
    public function processAudio2(ProcessAudio2 $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];

        $specific_activity_response = $input['audio_specific_activity'];
        $env_pp_response            = $input['audio_partners_response'];
        $creative_lengths           = $input['audio_copy_lengths'];

        $campaign = Campaign::find($campaign_id);

        $campaign->products()->updateExistingPivot($input['product_id'], [
            'specific_activity_response' => $specific_activity_response,
            'contextual_env_pp_response' => $env_pp_response,
            'creative_lengths' => json_encode($creative_lengths)
        ]);

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign
        return response()->json([
            'message' => 'processed Audio 2 form successfully!'
        ]);
    }

    /**
     * Process brief submission video-1 (AJAX)
     */
    public function processVideo1(ProcessVideo1 $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign
        $campaign_id                = $input['campaign_id'];

        $campaign_objective         = $input['video_campaign_objective'];
        $primary_metric             = $input['video_primary_campaign_metric'];
        $primary_metric_goal_value  = $input['video_primary_metric_value'];
        $metric_2                   = $input['video_secondary_campaign_metric'];
        $metric_2_goal_value        = $input['video_secondary_metric_value'];


        $campaign = Campaign::find($campaign_id);

        $campaign->products()->updateExistingPivot($input['product_id'], [
            'campaign_objective' => $campaign_objective,
            'primary_metric' => $primary_metric,
            'primary_metric_goal_value' => $primary_metric_goal_value,
            'metric_2' => $metric_2,
            'metric_2_goal_value' => $metric_2_goal_value
        ]);

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully updated campaign with video product data'
        ]);
    }

    /**
     * Process brief submission video-2 (AJAX)
     */
    public function processVideo2(ProcessVideo2 $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign
        $campaign_id            = $input['campaign_id'];
        $geo_targeting          = $input['video_geo_targeting'];
        $demo_target            = $input['video_demo_target'];
        $creative_lengths       = $input['video_creative_lengths'];
        $creative_type          = $input['video_creative_types'];
        $interactive_creative_provider = $input['interactive_creative_provider'];

        if(array_key_exists('video_inventory_screen_types', $input)){
            $selected_inventory_screentypes = $input['video_inventory_screen_types'];
        }else{
            $selected_inventory_screentypes = array();
        }


        $inventory_screentypes = array(
            'Pre-Roll' => 'N',
            'In-read / Outstream' => 'N',
            'Broadcaster' => 'N',
            'YouTube TrueView' => 'N',
        );

        foreach ($selected_inventory_screentypes as $screentype){
            $inventory_screentypes[$screentype] = 'Y';
        }

        $campaign = Campaign::find($campaign_id);

        $campaign->products()->updateExistingPivot($input['product_id'], [
            'geo_targeting' => $geo_targeting,
            'video_demo_target' => $demo_target,
            'inventory_screentypes' => json_encode($inventory_screentypes),
            'creative_lengths' => json_encode($creative_lengths),
            'video_creative_type' => json_encode($creative_type),
            'interactive_creative_provider' => $interactive_creative_provider

        ]);

        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully processed video 2 form'
        ]);
    }

    /**
     * Process file upload and additional info form (AJAX)
     */
    public function processFileUpload(ProcessFileUpload $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        // retrieve campaign and brief
        $campaign = Campaign::where('id', $input['campaign_id'])->first();
        $brief = $campaign->brief;

        // campaign name
        $campaign_name = $brief->campaign_name;

        $additional_info            = $input['additional_notes'];
        $brief_response_deadline    = date('Y-m-d', strtotime($input['deadline']));


        $brief->additional_info = $additional_info;
        $brief->brief_response_deadline = $brief_response_deadline;
        $brief->save();

        if(array_key_exists('additional_files', $input)){
            $additional_files = $input['additional_files'];
        }else{
            $additional_files = array();
        }

        foreach ($additional_files as $file) {
            $original_filename = $file->getClientOriginalName();
            $unprocessed_filename = $campaign_name . '-' . $original_filename;
            $processed_filename = $this->clean($unprocessed_filename) . '.' . $file->guessExtension();

            $file_path = $file->storeAs('public/brief-files', $processed_filename, null, 'private');

            $brief->briefFiles()->create([
                'file_name' => $processed_filename,
                'location' => $file_path
            ]);
        }

        $status = Status::where('name', 'New Brief in Progress')->first();

        // write status to log table
        $status = $this->getNextStatus($campaign);

        // write status to log table
        // check that status is not null then save
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(\Auth::id());
            $log->user()->associate($user);

            $log->save();
        }else{
            // todo post brief updated comment
        }

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully processed file upload and additional info field'
        ]);
    }

    /**
     * Process submit brief form (AJAX)
     */
    public function processBriefSubmission(Request $request)
    {
        // Check user permission
        if(\Baselib::canCreateBrief() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add and edit briefs.'
            ]);
        }

        $input = $request->all();

        $campaign = Campaign::where('id', $input['campaign_id'])->first();

        // campaigns products
        $campaigns_products_ids = array();
        foreach ($campaign->products as $product){
            $campaigns_products_ids[] = $product->id;
        }

        $brief = $campaign->brief;
        $brief->user()->associate(User::findorFail(\Auth::id()));
        $brief->save();

        // get email recipients
        $activation_users               = $campaign->brief->getActivationUsers();
        $activation_line_manager_users  = $campaign->brief->getActivationLineManagers();

        // all activation users
        $all_activation_users = $activation_users->merge($activation_line_manager_users);

        $vod_users          = $campaign->brief->vodUsers;
        $vod_audio_ids      = array(Product::VOD, Product::AUDIO);
        // product ids for display, rich media and mobile
        $other_product_ids  = array(Product::DISPLAY, Product::RICH_MEDIA, Product::MOBILE);

        // boolean flag - does brief contain audio or vod product
        $contains_audio_vod         = (count(array_intersect($campaigns_products_ids, $vod_audio_ids)) > 0) ? true : false;

        // boolean flag - does brief contain display, rich media or mobile product
        $contains_other_products    =  (count(array_intersect($campaigns_products_ids, $other_product_ids)) > 0) ? true : false;

        $recipients = new Collection();

        if($contains_audio_vod){
            $recipients = $vod_users;
            if($contains_other_products){
                $recipients = $all_activation_users->merge($vod_users);
            }
        }else{
            $recipients = $all_activation_users;
        }

        if($campaign->status->id == Status::NEW_BRIEF){
            $status = Status::where('name', 'Brief Submitted')->first();

            // write status to log table
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user   = User::findorFail(\Auth::id());
            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Brief Submitted';
            $comment->body  = '<p><strong>Brief Submitted by '.$user->name.'</strong></p>';
            $comment->brief()->associate($brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();

            // send an email
            Mail::to($recipients)->send(new BriefSubmitted($campaign));
        }else{
            // send brief updated email
            Mail::to($recipients)->send(new BriefUpdated($campaign));

            // todo insert comment to indicate brief was updated

        }

        return response()->json([
            'message' => 'Successfully processed brief submission'
        ]);
    }

    /**
     * Process grid form (AJAX)
     */
    public function processGrid(ProcessGrid $request)
    {
        // Check user permission
        if(\Baselib::canUploadGrid() == false){
            return response()->json([
                'message' => 'Permission denied. Only activation users can add and edit briefs.'
            ]);
        }

        $input = $request->all();
        $is_successful = 0;

        $campaign = Campaign::where('id', $input['campaign_id'])->first();

        if(array_key_exists('targeting_grid', $input)){
            $targeting_grids = $input['targeting_grid'];
        }else{
            // return error
            return response()->json([
                'message' => 'Please upload a targeting grid',
                'is_successful' => $is_successful
            ]);
        }

        // loop through targeting grid files
        foreach ($targeting_grids as $targeting_grid){
            $original_filename = $targeting_grid->getClientOriginalName();

            $unprocessed_filename   = $campaign->brief->campaign_name . '-' . $original_filename;
            $processed_filename     = $this->clean($unprocessed_filename);
            $filename_ext           = $targeting_grid->getClientOriginalExtension();

            $i = 0;

            do {
                $i++;
                $full_processed_filename = $processed_filename.'-'.$i.'.'.$filename_ext;

            } while(Storage::disk('public')->exists('targeting-grids/'.$full_processed_filename));

            $file_path = $targeting_grid->storeAs('public/targeting-grids', $full_processed_filename, null, 'private');

            $campaign->grids()->create([
                'user_id'   => \Auth::user()->id,
                'file_name' => $full_processed_filename,
                'location'  => $file_path
            ]);

            $is_successful = 1;
        }

        $status = Status::where('name', 'Targeting Grid Uploaded')->first();

        // write status to log table
        $log = new Log();
        $log->status()->associate($status);
        $log->campaign()->associate($campaign);

        $user   = User::findorFail(\Auth::id());
        $log->user()->associate($user);
        $log->save();

        // add comment
        $comment = new Comment();
        $comment->title = 'Targeting Grid Uploaded';
        $comment->body  = '<p><strong>Targeting Grid Uploaded by '.$user->name.'</strong></p>';
        $comment->brief()->associate($campaign->brief);

        $system_comment_user = User::where('name', 'System Message')->first();
        $comment->author()->associate($system_comment_user);
        $comment->save();

        // get email recipients
        $activation_line_managers = $campaign->brief->getActivationLineManagers();

        // send an email
        Mail::to($activation_line_managers)->send(new TgUploaded($campaign));

        // return id of the campaign so that later forms in the workflow can use it to update/link correct records
        return response()->json([
            'message' => 'Successfully processed grid submission',
            'is_successful' => $is_successful
        ]);
    }

    /**
     * Process targeting grid form submission
     */
    public function processGridApprovalForm(Request $request)
    {
        $input_data = $request->all();
        $user   = User::findorFail(\Auth::id());

        // retrieve campaign
        $campaign = Campaign::where('id', $input_data['campaign_id'])->first();

        // identify what kind of submission it is
        $operation_type = $input_data['targeting-grid'];
        $alert_message = '';

        // contains the emails to be sent along with their recipients
        $email_types = array();

        switch ($operation_type){
            case 'lm-approve-grid':
                $status = Status::where('name', 'TG Approved by Line Manager')->first();
                $alert_message  = 'Targeting Grid approved.';
                $comment_body   = '<p><strong>Targeting Grid Approved by '.$user->name.'</strong></p>';

                if($campaign->isOver100k()){
                    $email_types[] = array(
                        'email_type' => new UpperTgApprovedByALM($campaign),
                        'recipients' => $campaign->brief->getHeadsOfActivationUsers()
                    );
                }else{
                    $email_types[] = array(
                        'email_type' => new TgApprovedByALM($campaign),
                        'recipients' => $campaign->brief->clientUsers
                    );

                    $email_types[] = array(
                        'email_type' => new ALMTGApprovalConfirmation($campaign),
                        'recipients' => $campaign->brief->getActivationUsers()
                    );
                }

                break;
//            case 'lm-reject-grid':
//                $status = Status::where('name', 'TG Rejected by Line Manager')->first();
//                $alert_message = 'Targeting Grid rejected.';
//                $comment_body   = '<p><strong>Targeting Grid Rejected by '.$user->name.'</strong></p>';
//
//                $activation_users = $campaign->brief->getActivationUsers();
//                $activation_line_manager_users = $campaign->brief->getActivationLineManagers();
//
//                $email_types[] = array(
//                    'email_type' => new TgRejectedByALM($campaign),
//                    'recipients' => $activation_users->merge($activation_line_manager_users)
//                );
//
//                break;
            case 'hoa-approve-grid':
                $status = Status::where('name', 'TG Approved by Head of Activation')->first();
                $alert_message  = 'Targeting Grid approved.';
                $comment_body   = '<p><strong>Targeting Grid Approved by '.$user->name.'</strong></p>';

                $email_types[] = array(
                    'email_type' => new TgApprovedByHoA($campaign),
                    'recipients' => $campaign->brief->clientUsers
                );

                $email_types[] = array(
                    'email_type' => new ALMTGApprovalConfirmation($campaign),
                    'recipients' => $campaign->brief->getActivationUsers()
                );

                break;
//            case 'hoa-reject-grid':
//                $status = Status::where('name', 'TG Rejected by Head of Activation')->first();
//                $alert_message  = 'Targeting Grid rejected.';
//                $comment_body   = '<p><strong>Targeting Grid Rejected by '.$user->name.'</strong></p>';
//
//                $activation_users = $campaign->brief->getActivationUsers();
//                $activation_line_manager_users = $campaign->brief->getActivationLineManagers();
//
//                $email_types[] = array(
//                    'email_type' => new TgRejectedByHoA($campaign),
//                    'recipients' => $activation_users->merge($activation_line_manager_users)
//                );
//                break;

            case 'agency-approve-grid':
                $status = Status::where('name', 'Targeting Grid Approved')->first();
                $alert_message = 'Targeting Grid approved.';
                $comment_body   = '<p><strong>Targeting Grid Approved by '.$user->name.'</strong></p>';

                $activation_users   = $campaign->brief->getActivationUsers();
                $vod_users          = $campaign->brief->vodUsers;

                $email_types[] = array(
                    'email_type' => new TgApprovedByAgency($campaign),
                    'recipients' => $activation_users->merge($vod_users)
                );

                break;
//            case 'agency-reject-grid':
//                $status = Status::where('name', 'TG Rejected by Agency User')->first();
//                $alert_message = 'Targeting Grid rejected.';
//                $comment_body   = '<p><strong>Targeting Grid Rejected by '.$user->name.'</strong></p>';
//
//
//                $activation_users               = $campaign->brief->getActivationUsers();
//                $activation_line_manager_users  = $campaign->brief->getActivationLineManagers();
//                $all_activation_users           = $activation_users->merge($activation_line_manager_users);
//
//                $vod_users                      = $campaign->brief->vodUsers;
//
//                $email_types[] = array(
//                    'email_type' => new TgRejectedByAgency($campaign),
//                    'recipients' => $all_activation_users->merge($vod_users)
//                );
//
//                break;

            default:
                $status = null;
        }

        // update campaign status
        // write status to log table
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Targeting Grid';
            $comment->body  = $comment_body;
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();
        }

        // Send emails
        foreach ($email_types as $email_type){
            $recipients = $email_type['recipients'];
            $email_type = $email_type['email_type'];

            Mail::to($recipients)->send($email_type);
        }

        alert()->success($alert_message)->autoclose(4000);
        return redirect('dashboard');
    }

    /**
     * Process booking approval form submission
     */
    public function processBookingApprovalForm(Request $request)
    {
        $user   = User::findorFail(\Auth::id());
        $input_data = $request->all();

        // retrieve campaign
        $campaign = Campaign::where('id', $input_data['campaign_id'])->first();

        // identify what kind of submission it is
        $operation_type = $input_data['booking'];
        $alert_message = 'An error occurred.';

        // contains the emails to be sent along with their recipients
        $email_types = array();

        switch ($operation_type){
            case 'at-approve-booking':
                $status         = Status::where('name', 'BF Approved by Act. Team')->first();
                $alert_message  = 'Booking form approved.';
                $comment_body   = '<p><strong>Booking Form Approved by '.$user->name.'</strong></p>';


                $email_types[] = array(
                    'email_type' => new ATApprovedBF($campaign),
                    'recipients' => $campaign->brief->getActivationLineManagers()
                );
                break;
            case 'at-reject-booking':
                $status = Status::where('name', 'BF Rejected by Act. Team')->first();
                $alert_message = 'Booking form rejected.';
                $comment_body   = '<p><strong>Booking Form Rejected by '.$user->name.'</strong></p>';

                $email_types[] = array(
                    'email_type' => new ATRejectedBF($campaign),
                    'recipients' => $campaign->brief->clientUsers
                );
                break;
            case 'lm-approve-booking':
                $status = Status::where('name', 'BF Approved by Act. Line Manager')->first();
                $alert_message = 'Booking form approved.';
                $comment_body   = '<p><strong>Booking Form Approved by '.$user->name.'</strong></p>';

                $email_types[] = array(
                    'email_type' => new ALMApprovedBF($campaign),
                    'recipients' => $campaign->brief->getActivationUsers()
                );

                $email_types[] = array(
                    'email_type' => new ALMApprovedBFAgeConf($campaign),
                    'recipients' => $campaign->brief->clientUsers
                );
                break;
            case 'lm-reject-booking':
                $status = Status::where('name', 'BF Rejected by Act. Line Manager')->first();
                $alert_message  = 'Booking form rejected.';
                $comment_body   = '<p><strong>Booking Form Rejected by '.$user->name.'</strong></p>';

                $email_types[] = array(
                    'email_type' => new ALMRejectedBF($campaign),
                    'recipients' => $campaign->brief->getActivationUsers()
                );
                break;

            default:
                $status         = null;
                $comment_body   = null;
        }

        // update campaign status
        // write status to log table
        if($status !== null){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Booking Form';
            $comment->body  = $comment_body;
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();
        }

        // Send emails
        foreach ($email_types as $email_type){
            $recipients = $email_type['recipients'];
            $email_type = $email_type['email_type'];

            Mail::to($recipients)->send($email_type);
        }

        // show alert
        alert()->success($alert_message)->autoclose(4000);

        // redirect to dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Process IO submission (AJAX)
     */
    public function processIo(Request $request)
    {
        // check user permission
        if(\Baselib::canCreateIo() == false){
            return response()->json([
                'message' => 'Permission denied. Only activation and agency users can add IO data.'
            ]);
        }

        $operation_type     = 'Nothing saved.';

        $section_complete   = 0;

        $input = $request->all();

        // determine which products we should expect dsp data for
        $campaign_id = $input['campaign_id'];
        $campaign = Campaign::findOrFail($campaign_id);

        $campaigns_dsp_budgets_dds_codes = array();
        foreach ($campaign->bookingDetails as $booking_detail){
            foreach ($booking_detail->dspBudgets as $dsp_budget){
                $campaigns_dsp_budgets_dds_codes[] = $dsp_budget->dds_code;
            }
        }

        // identify what kind of submission it is
        $submission_type    = trim($input['submission_type']);

        // update dsp budgets with host links or dds codes and io files
        foreach ($input['dsp_budget'] as $dsp_budget_id => $io_data) {
            $dsp_budget = DspBudget::find($dsp_budget_id);

            if ($submission_type == 'save-links') {
                $dsp_budget->io_host_links = $io_data['host_links'];
                $dsp_budget->save();

                $operation_type = 'Links saved.';

            } elseif ($submission_type == 'save-ddscode-io'){
                $dsp_budget->dds_code = $io_data['dds_code'];

                // process file upload
                if (array_key_exists('io_file', $io_data)) {
                    $original_filename = $io_data['io_file']->getClientOriginalName();

                    $unprocessed_filename = $campaign->brief->campaign_name . '-' . $original_filename;
                    $processed_filename = $this->clean($unprocessed_filename) . '.' . $io_data['io_file']->getClientOriginalExtension(); // sketchy!

                    $file_path = $io_data['io_file']->storeAs('public/io', $processed_filename, null, 'private');

                    $dsp_budget->io_file_name = $processed_filename;
                    $dsp_budget->io_location = $file_path;
                    $dsp_budget->user_id = \Auth::user()->id;
                }
                $dsp_budget->save();

                $operation_type = 'DDS code saved.';
            }

        }

        // need to retrive campaign again to get latest updated record
        $campaign = Campaign::findOrFail($campaign_id);

        if (in_array($submission_type, array('save-links', 'save-ddscode-io'))) {
            if ($campaign->ioDdsCodesFilesComplete) {
                $section_complete = true;
            }
        }

        // write status to log table
        $send_email = false;
        if(in_array($submission_type, array('submit-io'))){
            $send_email = true;
            $status = Status::where('name', 'IO Uploaded')->first();

            // get email recipients
            $agency_recipients = $campaign->brief->clientUsers;
            $activation_recipients = $campaign->brief->getActivationUsers();

            $all_recipients = $agency_recipients->merge($activation_recipients);
            $email = new ActUploadedIos($campaign);

            if($send_email){
                // send email
                Mail::to($all_recipients)->send($email);
            }

            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user   = User::findorFail(\Auth::id());
            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'IO';
            $comment->body  = '<p><strong>IO Submitted by '.$user->name.'</strong></p>';
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();

            $operation_type = 'Submitted';
        }

        return response()->json([
            'message' => 'Updated DSP Budgets with IO data successfully.',
            'operation_type' => $operation_type,
            'section_complete' => $section_complete
        ]);
    }

    /**
     * Process tag form submission (AJAX)
     */
    public function processTags(ProcessTags $request)
    {
        // check user permission
        if(\Baselib::canCreateTags() == false){
            return response()->json([
                'message' => 'Permission denied. Only agency users can add Creative tags data.'
            ]);
        }

        $input = $request->all();
        $submission_type = trim($input['submission_type']);

        $campaign = Campaign::where('id', $input['campaign_id'])->first();

        if(array_key_exists('creative_tag', $input)){
            $creative_tags = $input['creative_tag'];
        }else{
            $creative_tags = array();
        }

        // loop through creative tag files
        foreach ($creative_tags as $creative_tag){
            $original_filename = $creative_tag->getClientOriginalName();

            $unprocessed_filename = $campaign->brief->campaign_name . '-' . $original_filename;
            $processed_filename = $this->clean($unprocessed_filename) . '.' . $creative_tag->getClientOriginalExtension(); // sketchy!

            $file_path = $creative_tag->storeAs('public/creative-tags', $processed_filename, null, 'private');

            $campaign->tags()->create([
                'user_id'   => \Auth::user()->id,
                'file_name' => $processed_filename,
                'location'  => $file_path,
                'file_type' => 'creative tag'
            ]);

        }

        // process pixel information file
        if(array_key_exists('pixel_info_1', $input)){
            $pixel_info_file = $input['pixel_info_1'];

            $original_filename = $pixel_info_file->getClientOriginalName();

            $unprocessed_filename = $campaign->brief->campaign_name . '-' . $original_filename;
            $processed_filename = $this->clean($unprocessed_filename) . '.' . $pixel_info_file->getClientOriginalExtension(); // sketchy!

            $file_path = $pixel_info_file->storeAs('public/creative-tags', $processed_filename, null, 'private');

            $campaign->tags()->create([
                'user_id'   => \Auth::user()->id,
                'file_name' => $processed_filename,
                'location'  => $file_path,
                'file_type' => 'pixel info'
            ]);
        }

        $brief = $campaign->brief;
        $brief->ct_file_share_links = $input['fileshare_links'];
        $brief->ct_pixel_info = $input['pixel_info_2'];
        $brief->save();
        $message = 'Creative Tags updated successfully.';

        if($submission_type == 'submit-campaign'){
            $status = Status::where('name', 'Uploaded Creative Tags')->first();

            // write status to log table
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user   = User::findorFail(\Auth::id());
            $log->user()->associate($user);
            $log->save();

            // add comment
            $comment = new Comment();
            $comment->title = 'Creative Tags';
            $comment->body  = '<p><strong>Creative Tags Submitted by '.$user->name.'</strong></p>';
            $comment->brief()->associate($campaign->brief);

            $system_comment_user = User::where('name', 'System Message')->first();
            $comment->author()->associate($system_comment_user);
            $comment->save();

            // send email
            // get email recipients
            $recipients = $campaign->brief->getActivationUsers();
            $email = new AgencyUploadedTags($campaign);

            Mail::to($recipients)->send($email);

            $message = 'Campaign Submitted successfully.';
        }


        return response()->json([
            'message' => $message,
        ]);
    }

    function clean($string) {

        // remove extension
        $string = strtolower(pathinfo($string, PATHINFO_FILENAME)); // remove extension and convert to lower case
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace('_', '-', $string); // Replaces all underscores with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    protected function getNextStatus(Campaign $campaign){
        // get current status of campaign
        $current_status = $campaign->status;

        // if null then status should be brief in progress
        if($current_status == null){
            $status = Status::where('name', 'New Brief in Progress')->first();
        }elseif ($current_status->id >= Status::NEW_BRIEF && $current_status->id <= Status::TG_REJECTED_BY_AGENCY_USER ){
            $status = null;
        }

        return $status;
    }

}