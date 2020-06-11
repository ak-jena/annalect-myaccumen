@if($campaign !== null)
    @section('subtitle', $campaign->brief->campaign_name)
@else
    @section('subtitle','Workflow Forms')
@endif

@extends('app')
@section('title', 'Home')

@section('content')

<script type="text/javascript">
    // brief urls
    var process_campaign_info_1_url = '@php echo route('campaign-info-1'); @endphp';
    var process_campaign_info_2_url = '@php echo route('campaign-info-2'); @endphp';
    var process_display_media_mobile_1_url = '@php echo route('display-media-mobile-1'); @endphp';
    var process_display_media_mobile_2_url = '@php echo route('display-media-mobile-2'); @endphp';
    var process_display_media_mobile_3_url = '@php echo route('display-media-mobile-3'); @endphp';
    var process_audio_1_url = '@php echo route('audio-1'); @endphp';
    var process_audio_2_url = '@php echo route('audio-2'); @endphp';
    var process_video_1_url = '@php echo route('video-1'); @endphp';
    var process_video_2_url = '@php echo route('video-2'); @endphp';
    var process_file_upload_url = '@php echo route('brief-file-upload'); @endphp';
    var brief_submit_url = '@php echo route('submit-brief'); @endphp';

    // targeting grid URL
    var process_grid_url = '@php echo route('process-grid'); @endphp';

    // booking URL
    var process_dsps_budgets_url = '@php echo route('process-dsp-submission'); @endphp';
    var process_date_change_url = '@php echo route('process-date-change'); @endphp';

    var delete_product_url = '@php echo route('delete-product'); @endphp';

    // creative tags URL
    var process_tags_url = '@php echo route('process-tags'); @endphp';

    // IO URL
    var process_io_url = '@php echo route('process-io'); @endphp';

    var dashboard_url = '@php echo route('dashboard'); @endphp';



    // determine if its a new or existing campaign
    var url_campaign_id = null;
    var brief_response_deadline = null;

    @php
        $user_id        = \Baselib::getRealUserID();
        $campaign_type  = null;
        $status_id      = 0;

        // default budget values (when new brief is being created)
        $audio_budget_value = '';
        $display_budget_value = '';
        $rich_media_budget_value = '';
        $mobile_budget_value = '';
        $vod_budget_value = '';
        $total_budget_value = '';

        // objectives
        $audio_objective = 'Awareness';
        $display_objective = '';
        $rich_media_objective = '';
        $mobile_objective = '';
        $vod_objective = '';

        // primary metrics
        $audio_primary_metric = 'LTR';
        $vod_primary_metric = '';

        // primary metric values
        $audio_primary_metric_value = '80% +';
        $vod_primary_metric_value = '';

        // secondary metrics
        $vod_secondary_metric = '';
        $vod_secondary_metric_value = '';

        // geo targeting values
        $audio_geo_value = '';
        $vod_geo_value = '';

        $audio_geo_details = '';

        // inventory/screentypes
        $audio_inventory = array();
        $vod_inventory = array();

        // specific activity and environments publishing partners
        $audio_specific_activity = '';
        $audio_env_pp = '';

        // copy length
        $audio_copy_length = array();
        $vod_copy_length = array();

        // vod exclusive fields
        $vod_frequency_capping = '';
        $vod_nielson_dar_tracking = '';
        $vod_demo_target = '';
        $vod_creative_type = array();
        $vod_interactive_provider = '';

        // display, rich media and mobile
        $drm_objective              = '';
        $drm_primary_metric         = '';
        $drm_primary_metric_value   = '';
        $drm_geo_value              = '';
        $drm_geo_details            = '';
        $drm_inventory              = array();
        $drm_specific_activity      = '';
        $drm_env_pp                 = '';

        $drm_act_1          = '';
        $drm_act_1_metric   = '';
        $drm_act_1_value    = '';
        $drm_act_2          = '';
        $drm_act_2_metric   = '';
        $drm_act_2_value    = '';
        $drm_act_3          = '';
        $drm_act_3_metric   = '';
        $drm_act_3_value    = '';

        $additional_info            = '';
        $brief_response_deadline    = '';

        $number_of_brief_file_fields = array(0,1,2);
        $existing_brief_files = null;
        $existing_brief_files_arr = array();

        if($campaign !== null){
            // this sets the javascript variable
            echo 'var url_campaign_id = '.$campaign->id.';';

            $status_id                  = $campaign->status->id;

            $user_id                    = $campaign->brief->user->id;
            $campaign_type              = $campaign->brief->campaign_type;
            $additional_info            = $campaign->brief->additional_info;
            $brief_response_deadline    = $campaign->brief->brief_response_deadline;

            if($brief_response_deadline != null){
                $deadline_date_array = explode('-', $brief_response_deadline);
                $deadline_year = $deadline_date_array[0];
                $deadline_month = $deadline_date_array[1]-1;
                $deadline_day = $deadline_date_array[2];
                echo 'var brief_response_deadline = new Date('.$deadline_year.','.$deadline_month.','.$deadline_day.');';
            }

            $total_budget_value = 0;

            // retrieve budget values
            foreach($campaign->products as $product){
                $product_budget         = $product->pivot->budget;
                $product_objective      = $product->pivot->campaign_objective;
                $primary_metric         = $product->pivot->primary_metric;
                $primary_metric_value   = $product->pivot->primary_metric_goal_value;
                $geo_targeting          = $product->pivot->geo_targeting;
                $geo_targeting_details  = $product->pivot->geo_targeting_details;
                $inventory              = json_decode($product->pivot->inventory_screentypes, true);
                $specific_activity      = $product->pivot->specific_activity_response;
                $env_pp                 = $product->pivot->contextual_env_pp_response;
                $copy_length            = json_decode($product->pivot->creative_lengths, true);
                $secondary_metric       = $product->pivot->metric_2;
                $secondary_metric_value = $product->pivot->metric_2_goal_value;

                if($product->name == 'Audio'){
                    $audio_budget_value         = $product_budget;
                    $audio_objective            = $product_objective;
                    $audio_primary_metric       = $primary_metric;
                    $audio_primary_metric_value = $primary_metric_value;
                    $audio_geo_value            = $geo_targeting;
                    $audio_geo_details          = $geo_targeting_details;
                    $audio_inventory            = $inventory;
                    $audio_specific_activity    = $specific_activity;
                    $audio_env_pp               = $env_pp;
                    $audio_copy_length          = $copy_length;

                }elseif($product->name == 'VOD'){
                    $vod_budget_value           = $product_budget;
                    $vod_objective              = $product_objective;
                    $vod_primary_metric         = $primary_metric;
                    $vod_primary_metric_value   = $primary_metric_value;
                    $vod_geo_value              = $geo_targeting;
                    $vod_inventory              = $inventory;
                    $vod_copy_length            = $copy_length;
                    $vod_secondary_metric       = $secondary_metric;
                    $vod_secondary_metric_value = $secondary_metric_value;
                    $vod_frequency_capping      = $product->pivot->video_frequency_capping;
                    $vod_nielson_dar_tracking   = $product->pivot->video_nielson_dar_tracking;
                    $vod_demo_target            = $product->pivot->video_demo_target;
                    $vod_creative_type          = json_decode($product->pivot->video_creative_type, true);
                    $vod_interactive_provider   = $product->pivot->interactive_creative_provider;

                }elseif(in_array($product->name, array('Display', 'Rich Media', 'Mobile'))){
                    if($product->name == 'Display'){
                        $display_budget_value   = $product_budget;
                    }if($product->name == 'Rich Media'){
                        $rich_media_budget_value = $product_budget;
                    }if($product->name == 'Mobile'){
                        $mobile_budget_value    = $product_budget;
                    }
                    $drm_objective              = $product_objective;
                    $drm_primary_metric         = $primary_metric;
                    $drm_primary_metric_value   = $primary_metric_value;
                    $drm_geo_value              = $geo_targeting;
                    $drm_geo_details            = $geo_targeting_details;
                    $drm_inventory              = $inventory;
                    $drm_specific_activity      = $specific_activity;
                    $drm_env_pp                 = $env_pp;

                    $drm_act_1          = $product->pivot->display_media_mobile_activity_1;
                    $drm_act_1_metric   = $product->pivot->display_media_metric_activity_1;
                    $drm_act_1_value    = $product->pivot->display_media_mobile_value_1;

                    $drm_act_2          = $product->pivot->display_media_mobile_activity_2;
                    $drm_act_2_metric   = $product->pivot->display_media_metric_activity_2;
                    $drm_act_2_value    = $product->pivot->display_media_mobile_value_2;

                    $drm_act_3          = $product->pivot->display_media_mobile_activity_3;
                    $drm_act_3_metric   = $product->pivot->display_media_metric_activity_3;
                    $drm_act_3_value    = $product->pivot->display_media_mobile_value_3;
                }

                $total_budget_value += $product_budget;

            }
            $existing_brief_files = $campaign->brief->briefFiles;
            $existing_brief_files_arr = $campaign->brief->briefFiles->toArray();

            $number_of_brief_file_fields = array(0,1,2);

        }
    @endphp


    var csrf_token = '{{ csrf_token() }}';

    $(document).ready(function() {
       
        // this snippet records whenever a form button is clicked 
        // and records the value has a hidden input
        $(document).on('click', '[name][value]:button', function(evt){
            var $button = $(evt.currentTarget),
                $input = $button.closest('form').find('input[name="'+$button.attr('name')+'"]');
            
            if(!$input.length){
                $input = $('<input>', {
                    type:'hidden',
                    name:$button.attr('name')
                });
                $input.insertAfter($button);
            }

            $input.val($button.val());
        });
    });

</script>
<?php
$products = DB::table('products')->orderBy('name','asc')->pluck('name', 'id');
?>

    <div class="container">

        @php
            $status = null;
            $campaign_id = 0;
        @endphp
        @if($campaign !== null )
            @php $campaign_id = $campaign->id; @endphp
            @if($campaign->status !== null)
                @php $status = $campaign->status; @endphp
                @if($status->id == \App\Status::CAMPAIGN_CANCELLED)
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="alert alert-danger" role="alert"><p>This campaign has been cancelled.</p></div>
                        </div>

                    </div>
                @endif
            @endif

            @php
                $brief = $campaign->brief;
                $campaign_products = $campaign->products()->orderBy('name','asc')->pluck('id', 'name');

                $start_date = date('d-m-Y', strtotime($brief->start_date));
                $end_date = date('d-m-Y', strtotime($brief->end_date));

                $dates = $start_date.' - '.$end_date;
            @endphp
        @else
            @php
                $brief = new \App\Brief();
                if($duplicate_brief !== null){
                    $campaign_products = $duplicate_products;

                    $brief = $duplicate_brief;
                    $brief->client()->dissociate();
                    $brief->campaign_name   = null;
                    $brief->start_date      = null;
                    $brief->end_date        = null;
                    $dates                  = '';

                    foreach($brief->campaign->products as $product){
                        $product_objective      = $product->pivot->campaign_objective;
                        $primary_metric         = $product->pivot->primary_metric;
                        $primary_metric_value   = $product->pivot->primary_metric_goal_value;
                        $geo_targeting          = $product->pivot->geo_targeting;
                        $geo_targeting_details  = $product->pivot->geo_targeting_details;
                        $inventory              = json_decode($product->pivot->inventory_screentypes, true);
                        $specific_activity      = $product->pivot->specific_activity_response;
                        $env_pp                 = $product->pivot->contextual_env_pp_response;
                        $copy_length            = json_decode($product->pivot->creative_lengths, true);
                        $secondary_metric       = $product->pivot->metric_2;
                        $secondary_metric_value = $product->pivot->metric_2_goal_value;

                        if($product->name == 'Audio'){
                            $audio_objective            = $product_objective;
                            $audio_primary_metric       = $primary_metric;
                            $audio_primary_metric_value = $primary_metric_value;
                            $audio_geo_value            = $geo_targeting;
                            $audio_geo_details          = $geo_targeting_details;
                            $audio_inventory            = $inventory;
                            $audio_specific_activity    = $specific_activity;
                            $audio_env_pp               = $env_pp;
                            $audio_copy_length          = $copy_length;

                        }elseif($product->name == 'VOD'){
                            $vod_objective              = $product_objective;
                            $vod_primary_metric         = $primary_metric;
                            $vod_primary_metric_value   = $primary_metric_value;
                            $vod_geo_value              = $geo_targeting;
                            $vod_inventory              = $inventory;
                            $vod_copy_length            = $copy_length;
                            $vod_secondary_metric       = $secondary_metric;
                            $vod_secondary_metric_value = $secondary_metric_value;
                            $vod_frequency_capping      = $product->pivot->video_frequency_capping;
                            $vod_nielson_dar_tracking   = $product->pivot->video_nielson_dar_tracking;
                            $vod_demo_target            = $product->pivot->video_demo_target;
                            $vod_creative_type          = json_decode($product->pivot->video_creative_type, true);
                            $vod_interactive_provider   = $product->pivot->interactive_creative_provider;

                        }elseif(in_array($product->name, array('Display', 'Rich Media', 'Mobile'))){
                            $drm_objective              = $product_objective;
                            $drm_primary_metric         = $primary_metric;
                            $drm_primary_metric_value   = $primary_metric_value;
                            $drm_geo_value              = $geo_targeting;
                            $drm_geo_details            = $geo_targeting_details;
                            $drm_inventory              = $inventory;
                            $drm_specific_activity      = $specific_activity;
                            $drm_env_pp                 = $env_pp;

                            $drm_act_1          = $product->pivot->display_media_mobile_activity_1;
                            $drm_act_1_metric   = $product->pivot->display_media_metric_activity_1;
                            $drm_act_1_value    = $product->pivot->display_media_mobile_value_1;

                            $drm_act_2          = $product->pivot->display_media_mobile_activity_2;
                            $drm_act_2_metric   = $product->pivot->display_media_metric_activity_2;
                            $drm_act_2_value    = $product->pivot->display_media_mobile_value_2;

                            $drm_act_3          = $product->pivot->display_media_mobile_activity_3;
                            $drm_act_3_metric   = $product->pivot->display_media_metric_activity_3;
                            $drm_act_3_value    = $product->pivot->display_media_mobile_value_3;
                        }
                    }
                }else{
                    $campaign_products = new \Illuminate\Database\Eloquent\Collection();
                }
                $dates = '';
            @endphp
        @endif


        <div class="panel-group" id="workflow-accordion" role="tablist" aria-multiselectable="true">
            @foreach($sections as $section)
                @php
                    $expanded       = 'aria-expanded="false"';
                    $css_class      = '';
                    $heading_class  = '';
                    if(in_array($section->id, $active_section)){
                        $expanded = 'aria-expanded="true"';
                        $css_class  = 'in';
                        $heading_class  = 'active-title';
                    }
                @endphp

                <div class="panel panel-default accuen-panel" id="campaign-accordion">
                    <div class="panel-heading {{ $heading_class }}" role="tab" id="heading-{{ $section->level }}">
                        <a role="button" data-toggle="collapse" data-parent="#campaign-accordion" href="#collapse-{{ $section->level }}" aria-expanded="false" aria-controls="collapse-{{ $section->level }}">
                            <h2 id="header-{{ $section->level }}" {{ $expanded }} class="accuen-title panel-title text-center">
                                {{ $section->name }}
                            </h2>
                            <div class="text-center accuen-down">
                                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
                            </div>
                        </a>
                    </div>
                    <div id="collapse-{{ $section->level }}" class="panel-collapse collapse {{ $css_class }}" role="tabpanel" aria-labelledby="heading--{{ $section->level }}">
                        <div class="panel-body">
                            @php
                                $panelFileName = strtolower(str_replace(' ','-',$section->name));
                                $panelFilePath = 'panel-content.'.$panelFileName;
                            @endphp
                            @include($panelFilePath)
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($status != null)
            @if($status->id != \App\Status::CAMPAIGN_CANCELLED)
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a class="btn btn-danger btn-lg" href="{{ route('cancel-campaign-form', ['campaign_id'=>$campaign->id]) }}" role="button">Cancel Campaign</a>
                    </div>

                </div>
            @endif
        @endif

    </div>

@endsection
