@php
    $creative_considerations = array(
        ':05s' => ':05s',
        ':10s' => ':10s',
        ':15s' => ':15s',
        ':20s' => ':20s',
        ':30s' => ':30s',
        ':45s' => ':45s',
        ':60s' => ':60s',
        'Other' => 'Other'
    );

    $audio_creative_considerations = array(
        ':15s' => ':15s',
        ':20s' => ':20s',
        ':30s' => ':30s',
        ':40s' => ':40s',
        ':60s' => ':60s'
    );

    $video_creative_types = array(
        'VAST' => 'VAST',
        'V-Paid' => 'V-Paid',
        'In Stream' => 'In Stream',
        '3rd Party served + V-Paid' => '3rd Party served + V-Paid',
        'Youtube' => 'Youtube'
    );

    $geo_targeting = array(
        'UK' => 'UK',
        'UK excluding NI' => 'UK excluding NI',
        'Specific cities' => 'Specific cities',
        'Specific Postcodes' => 'Specific Postcodes',
        'Other' => 'Other'
    );

    $activity_metrics = array(
        'CPA' => 'CPA',
        'CTR' => 'CTR',
        'CPC' => 'CPC',
        'CPL' => 'CPL',
        'CPE' => 'CPE',
        'ROI' => 'ROI',
        'CPV' => 'CPV',
        'Completion rate' => 'Completion rate',
        'Audience Reach' => 'Audience Reach',
        '% In-view delivery' => '% In-view delivery'
    );

    $inventory_screentypes = array(
        'Desktop' => 'Desktop',
        'Tablet' => 'Tablet',
        'Mobile Web' => 'Mobile Web',
        'Mobile App' => 'Mobile App',
        'Native' => 'Native'
    );

    $video_inventory_screentypes = array(
        'Pre-Roll' => 'Pre-Roll',
        'In-read / OutStream' => 'In-read / OutStream',
        'Broadcaster' => 'Broadcaster',
        'YouTube TrueView' => 'YouTube TrueView'
    );

    $drm_video_campaign_objectives = array(
        'Online Acquisition' => 'Online Acquisition',
        'Ad Engagement' => 'Ad Engagement',
        'OnLine Leads' => 'OnLine Leads',
        'Site Lists' => 'Site Lists',
        'Audience Reach' => 'Audience Reach'
    );

    $video_primary_metrics = array(
        'To be supplied' => 'To be supplied'
    );

    $logged_in_user_id = \Baselib::getRealUserID();

    $unsure_dsp = true;
    $vod_tube_mogul_budget = '';
    $vod_aol_budget = '';
    $vod_dbm_budget_budget = '';
    $vod_amazon_budget = '';
    $vod_the_tradedesk_budget = '';
    $vod_videology_budget = '';
    $vod_dbm_budget = '';
    $vod_brightroll_budget = '';

    // retrieve existing vod dsps
    if($booking_details !== null){

        $vod_booking = $booking_details->where('product_id', App\Product::VOD)->first();
        if(count($vod_booking) > 0){
            $vod_tube_mogul_budget      = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::TUBE_MOGUL)->value('budget') ?:  0;
            $vod_aol_budget             = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::AOL)->value('budget') ?:  0;
            $vod_dbm_budget_budget      = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM_TV)->value('budget') ?:  0;
            $vod_amazon_budget          = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::AMAZON)->value('budget') ?:  0;
            $vod_the_tradedesk_budget   = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::TRADEDESK)->value('budget') ?:  0;
            $vod_videology_budget       = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::VIDEOLOGY)->value('budget') ?:  0;
            $vod_dbm_budget             = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM)->value('budget') ?:  0;
            $vod_brightroll_budget      = $vod_booking->dspBudgets()->where('dsp_id', \App\Dsp::BRIGHTROLL)->value('budget') ?:  0;

            $vod_total = round($vod_tube_mogul_budget + $vod_aol_budget + $vod_dbm_budget_budget + $vod_amazon_budget + $vod_the_tradedesk_budget + $vod_videology_budget + $vod_dbm_budget + $vod_brightroll_budget, 2);

            if($vod_total > 0){
                $unsure_dsp = false;
            }
        }
    }

@endphp

    <div class="row">

        <div class="sliding-content-container">
            <div class="inner-container">

                {{--key campaign info part 1--}}
                <div class="sliding-content" id="campaign-info-1-slide">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Key Campaign Information 1</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
{{--                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('cancel-campaign-form', ['campaign_id'=>$campaign->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a title="Comment" style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>

                                    @if($status_id >= \App\Status::BRIEF_SUBMITTED)
                                        <a title="Export Brief" style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('export-brief', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-export" aria-hidden="true"></span></a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    {!! Form::model(
                        $brief,
                        [
                            'route' => 'campaign-info-1',
                            'class' => 'form-horizontal',
                            'id' => 'campaign-info-form-1'
                        ])
                    !!}

                    {!! Form::hidden('operation_type', null, array('id' => 'operation_type')) !!}
                    @if ($campaign == null)
                        <div class="form-group">
                            {!! Form::label('existing_campaign', 'Create from Existing Brief', array('class' => 'col-sm-3 control-label')) !!}

                            <div class="col-sm-6">
                                {!! Form::select('existing_campaign', $existing_campaign_names, $duplicate_brief_id, array('class' => 'col-sm-3 form-control', 'placeholder' => '-')) !!}
                            </div>

                        </div>
                    @endif

                    <div id="product" class="form-group">
                        {!! Form::label('product[]', 'Product(s)', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            @foreach ($products as $product_id => $product)
                                @php $selected = false; @endphp

                                @if ($campaign_products->contains($product_id))
                                    @php $selected = true; @endphp
                                @endif

                                <label class="checkbox-inline">
                                    {!! Form::checkbox('product[]', $product_id, $selected) !!} {{ $product }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <?php
                        $advertisers =  \Baselib::getUser($logged_in_user_id)->permittedClients->pluck('name', 'id');
                    ?>
                    <div id="client_id" class="form-group">
                        {!! Form::label('client_id', 'Advertiser', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::select('client_id', $advertisers, null, array('class' => 'form-control', 'placeholder' => 'Please select')) !!}
                        </div>
                    </div>

                    <?php
                        // get logged in user
                        $logged_in_user = \Baselib::getUser($logged_in_user_id);

                        $users = $logged_in_user->usersWithinAgency->sortBy('name')->pluck('name', 'id');
                    ?>
                    <div id="user_id" class="form-group">
                        {!! Form::label('user_id', 'Agency Contact', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::select('user_id', $users, $user_id, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group" id="campaign_name">
                        {!! Form::label('campaign_name', 'Campaign Name', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::text('campaign_name', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group" id="campaign_type">
                        {!! Form::label('campaign_type', 'Campaign Type', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::select('campaign_type', array('Brand' => 'Brand', 'Performance' =>  'Performance'), $campaign_type, array('class' => 'form-control', 'placeholder' => 'Please select')) !!}
                        </div>
                    </div>

                    <div class="form-group" id="campaign_dates">
                        {!! Form::label('campaign_dates', 'Dates', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::text('campaign_dates', $dates, array('class' => 'form-control', 'id' => 'campaign_dates')) !!}
                        </div>
                    </div>

                    <div class="form-group" id="flighting_considerations">
                        {!! Form::label('flighting_considerations', 'Flighting Considerations', array('class' => 'col-sm-3 control-label required')) !!}
                        <div class="col-sm-6 field">
                            {!! Form::textarea('flighting_considerations', null, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="i.e. dayparting"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <!-- Add Buttons -->
                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            @if ($campaign == null || $status_id < \App\Status::TARGETING_GRID_APPROVED)
                                <?php if(\Baselib::canCreateBrief()): ?>
                                    <button id="campaign-info-1-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default" name="brief">
                                        <span class="ladda-label">Save and proceed <i class="glyphicon glyphicon-chevron-right"></i></span>
                                    </button>
                                <?php endif; ?>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}

                    @if ($campaign !== null )
                        <a class="btn btn-default next-button btn-sm" href="#" role="button">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </a>
                    @endif
                </div>

                {{--key campaign info part 2--}}
                <div class="sliding-content" id="campaign-info-2-slide">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Key Campaign Information 2</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        Note: Please ensure all ASBOF, Agency commission, adserving etc is taken out before giving us the Agency NET Budget
                    </div>

                    {!! Form::model(
                        $brief,
                        [   'route' => 'campaign-info-2',
                            'class' => 'form-horizontal',
                            'id' => 'campaign-info-form-2'
                        ]
                        )
                    !!}

                    {!! Form::hidden('campaign_id', null, array('id' => 'campaign_id')) !!}

                    <div class="form-group">
                        <div id="audio_budget" class="budget-fields">
                            {!! Form::label('audio_budget', 'Audio Budget', array('class' => 'required col-sm-3 control-label')) !!}
                            <div class="col-sm-2 field">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    {!! Form::text('audio_budget', $audio_budget_value, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="help-text col-sm-1"><button type="button" id="audio_help" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Minimum spend: £10,000"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                        </div>


                        <div id="display_budget" class="budget-fields">
                            {!! Form::label('display_budget', 'Display Budget', array('class' => 'required col-sm-3 control-label')) !!}
                            <div class="col-sm-2 field">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    {!! Form::text('display_budget', $display_budget_value, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="help-text col-sm-1"><button type="button" id="display_help" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Minimum spend: £10,000"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="rich_media_budget" class="budget-fields">
                            {!! Form::label('rich_media_budget', 'Rich Media Budget', array('class' => 'required col-sm-3 control-label')) !!}
                            <div class="col-sm-2 field">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    {!! Form::text('rich_media_budget', $rich_media_budget_value, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="help-text col-sm-1"><button type="button" id="rich_media_help" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Minimum spend: £11,000 - Based on provider and format."><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                        </div>
                        <div id="mobile_budget" class="budget-fields">
                            {!! Form::label('mobile_budget', 'Mobile Budget', array('class' => 'required col-sm-3 control-label')) !!}
                            <div class="col-sm-2 field">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    {!! Form::text('mobile_budget', $mobile_budget_value, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="help-text col-sm-1"><button type="button" id="mobile_help" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Minimum spend: £10,000"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div id="vod_budget" class="budget-fields">
                            {!! Form::label('vod_budget', 'VOD Budget', array('class' => 'required col-sm-3 control-label')) !!}
                            <div class="col-sm-2 field">
                                <div class="input-group">
                                    <span class="input-group-addon">£</span>
                                    {!! Form::text('vod_budget', $vod_budget_value, array('class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="help-text col-sm-1"><button type="button" id="vod_help" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Minimum spend: £10,000"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                        </div>
                    </div>

                    <div id="vod-dsp-budget" class="form-group">

                        @foreach ($vod_dsps as $vod_dsp)
                            @php
                                $dsp_name = $vod_dsp->dsp_name;
                                $formatted_dsp_name = str_replace(' ','_', strtolower($dsp_name));
                                $formatted_dsp_name = str_replace(array('(',')'),'', $formatted_dsp_name);

                            @endphp

                            <div id="vod_dsp_{{ $vod_dsp->id }}" class="vod-dsp-budget-fields">

                                {!! Form::label('vod_dsp['.$vod_dsp->id.']', ucfirst($dsp_name).' Budget', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-2 field">
                                    <div class="input-group">
                                        <span class="input-group-addon">£</span>
                                        {!! Form::text('vod_dsp['.$vod_dsp->id.']', ${'vod_'.$formatted_dsp_name.'_budget'}, ['id' => 'planned_vod_dsp_budget_'.$formatted_dsp_name, 'class' => 'dsp-budget form-control']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group" id="vod-dsp-unsure">
                        <div class="col-sm-2 col-sm-offset-3">
                            {!! Form::checkbox('vod_dsp_unsure', 1, $unsure_dsp, array('id' => 'vod_dsp_unsure')) !!} Not Sure
                        </div>
                    </div>

                    <div class="form-group" id="total_budget">
                        {!! Form::label('total_budget', 'Total Budget', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-7 field">
                            <div class="input-group">
                                <span class="input-group-addon">£</span>
                                {!! Form::text('total_budget', number_format(floatval($total_budget_value),2), array('class' => 'form-control', 'readonly')) !!}
                            </div>
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please note anything under £5,000 per month needs approval by OMG Programmatic"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="background">
                        {!! Form::label('background', 'Background to Brief', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-7 field">
                            {!! Form::textarea('background', null, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please provide summary of reason for campaign, agency media strategy we should adhere to, and any historical learnings we should take into consideration"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="target_audience_profile">
                        {!! Form::label('target_audience_profile', 'Target Audience profile', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-7 field">
                            {!! Form::textarea('target_audience_profile', null, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please note this is for planning insight at this stage and not a specific targeting commitment"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" id="campaign-info-2-submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        <span class="ladda-label">Save and proceed <i class="glyphicon glyphicon-chevron-right"></i></span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--display, rich media and mobile part 1--}}
                <div class="sliding-content" id="display-media-mobile-1-slide">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Display, Rich Media and Mobile 1</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'display-media-mobile-1',
                        'class' => 'form-horizontal',
                        'id' => 'display-media-mobile-1'
                    ]) !!}

                    {!! Form::hidden('products_ids[]', null, array('id' => 'products_ids')) !!}

                    <div class="form-group">
                        {!! Form::label('campaign_objective', 'Campaign Objective', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('campaign_objective', $drm_video_campaign_objectives, $drm_objective, array('class' => 'form-control') ) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="(e.g. product launch, sales driving, etc"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="primary_campaign_metric">
                        {!! Form::label('primary_campaign_metric', 'Primary Campaign Metric', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('primary_campaign_metric', $activity_metrics, $drm_primary_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="select from dropdown"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group med-margin-bottom" id="metric_goal_value">
                        {!! Form::label('metric_goal_value', 'Metric Goal Value', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('metric_goal_value', $drm_primary_metric_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="e.g. target CPA, Target in-view Threshold etc"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="activity_1">
                        {!! Form::label('activity_1', 'Activity 1 (e.g. High impact banners)', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_1', $drm_act_1, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('activity_1_metric', 'Activity Metric', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('activity_1_metric', $activity_metrics, $drm_act_1_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="select from drop down"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group med-margin-bottom">
                        {!! Form::label('activity_1_goal_value', 'Metric Goal Value', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_1_goal_value', $drm_act_1_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="e.g. target CPA, Target in-view Threshold etc"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="display-media-mobile-1-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--display, rich media and mobile part 2--}}
                <div class="sliding-content" id="display-media-mobile-2-slide">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Display, Rich Media and Mobile 2</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'display-media-mobile-2',
                        'class' => 'form-horizontal',
                        'id' => 'display-media-mobile-2'
                    ]) !!}

                    <div class="form-group">
                        {!! Form::label('activity_2', 'Activity 2', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_2', $drm_act_2, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('activity_2_metric', 'Activity Metric', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('activity_2_metric', $activity_metrics, $drm_act_2_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="select from drop down"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group med-margin-bottom">
                        {!! Form::label('activity_2_goal_value', 'Metric Goal Value', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_2_goal_value', $drm_act_2_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="e.g. target CPA, Target in-view Threshold etc"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('activity_3', 'Activity 3', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_3', $drm_act_3, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('activity_3_metric', 'Activity Metric', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('activity_3_metric', $activity_metrics, $drm_act_3_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="select from drop down"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('activity_3_goal_value', 'Metric Goal Value', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('activity_3_goal_value', $drm_act_3_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="e.g. target CPA, Target in-view Threshold etc"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="display-media-mobile-2-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                    {{--<button type="submit" class="btn btn-default" name="brief" value="submission">--}}
                                    {{--<i class="fa fa-plus"></i> Submit Brief--}}
                                    {{--</button>--}}

                                    {{--<button type="submit" class="btn btn-default" name="brief" value="draft">--}}
                                    {{--<i class="fa fa-plus"></i> Save Brief Draft--}}
                                    {{--</button>--}}
                                </div>
                            </div>
                        @endif
                    @endif
                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                     @if($status_id >= \App\Status::BRIEF_SUBMITTED)
                        <a class="btn btn-default next-button btn-sm" href="#" role="button">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                        </a>
                    @endif
                </div>

                {{--display, rich media and mobile part 3--}}
                <div class="sliding-content" id="display-media-mobile-3-slide">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Display, Rich Media and Mobile 3</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'display-media-mobile-3',
                        'class' => 'form-horizontal',
                        'id' => 'display-media-mobile-3'
                    ]) !!}

                    <div class="form-group">
                        {!! Form::label('geo_targeting', 'Geo Targeting', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('geo_targeting', $geo_targeting, null, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="UK by default adjust via drop down"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('geo_targeting_details', 'Geo Targeting Details', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('geo_targeting_details', $drm_geo_details, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="if specific regions or postcodes or non-UK"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('inventory_screentypes[]', 'Inventory/Screen Type(s)', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            @foreach ($inventory_screentypes as $type_id => $type)

                                @php
                                    $selected = false;
                                    if(count($drm_inventory) > 0){
                                        if($drm_inventory[$type] == 'Y'){
                                            $selected = true;
                                        }
                                    }
                                @endphp

                                <label class="checkbox-inline">
                                    {!! Form::checkbox('inventory_screentypes[]', $type_id, $selected) !!} {{ $type }}
                                </label>
                            @endforeach
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Uncheck if out of scope"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('specific_activity', 'Any specific activity we should consider in a response', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('specific_activity', $drm_specific_activity, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please refer to OMG Programmatic Product One Pagers for potential options. Please note consideration of a targeting type is not a guarantee of inclusion"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('partners_response', 'Environments/Publisher partners to consider in response', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('partners_response', $drm_env_pp, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please note due to RTB non-guraranteed buying methodology specific volume on any site/publisher cannot be guaranteed"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="display-media-mobile-3-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--audio part 1--}}
                <div class="sliding-content" id="audio-1">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Audio 1</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'audio-1',
                        'class' => 'form-horizontal',
                        'id' => 'audio-1'
                    ]) !!}

                    {!! Form::hidden('product_id', 4, array('id' => 'product_id')) !!}

                    <div class="form-group" id="audio_campaign_objective">
                        {!! Form::label('audio_campaign_objective', 'Campaign Objective', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('audio_campaign_objective', $audio_objective, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please note: audio campaigns are to drive awareness only"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="audio_primary_campaign_metric">
                        {!! Form::label('audio_primary_campaign_metric', 'Primary Campaign Metric', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('audio_primary_campaign_metric', $audio_primary_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="LTR is the only metric we can measure across audio"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="audio_metric_goal_value">
                        {!! Form::label('audio_metric_goal_value', 'Metric Goal Value', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('audio_metric_goal_value', $audio_primary_metric_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="We always aim to achieve a 80% or above Listen-thru rate"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="audio_geo_targeting">
                        {!! Form::label('audio_geo_targeting', 'Geo Targeting', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('audio_geo_targeting', $geo_targeting, $audio_geo_value, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="UK by default - adjust via drop down"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('audio_geo_targeting_details', 'Geo Targeting Details', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('audio_geo_targeting_details', $audio_geo_details, array('class' => 'form-control','cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="if specific regions or postcodes or non-UK"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('audio_has_companion_banner', 'Will you include a companion banner?', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('audio_has_companion_banner', [0 => 'No', 1 => 'Yes'], null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
                        </div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="audio-1-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--audio part 2--}}
                <div class="sliding-content" id="audio-2">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Audio 2</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'audio-2',
                        'class' => 'form-horizontal',
                        'id' => 'audio-2'
                    ]) !!}

                    {!! Form::hidden('product_id', 4, array('id' => 'product_id')) !!}

                    <div class="form-group">
                        {!! Form::label('audio_specific_activity', 'Any specific activity we should consider in a response', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('audio_specific_activity', $audio_specific_activity, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please refer to OMG Programmatic Product One Pagers for potential options. Please note consideration of a targeting type is not a guarantee of inclusion"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('audio_partners_response', 'Environments/Publisher partners to consider', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('audio_partners_response', $audio_env_pp, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please note due to RTB non-guraranteed buying methodology specific volume on any site/publisher cannot be guaranteed"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="audio_copy_lengths">
                        {!! Form::label('audio_copy_lengths', 'Copy Length', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            @foreach ($audio_creative_considerations as $audio_creative_consideration)
                                @php
                                    $selected = false;
                                    if(count($audio_copy_length) > 0){
                                        if(in_array($audio_creative_consideration, $audio_copy_length)){
                                            $selected = true;
                                        }
                                    }
                                @endphp
                                <label class="checkbox-inline">
                                    {!! Form::checkbox('audio_copy_lengths[]', $audio_creative_consideration, $selected) !!} {{ $audio_creative_consideration }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="audio-2-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--video part 1--}}
                <div class="sliding-content" id="video-1">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Programmatic Video 1</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'video-1',
                        'class' => 'form-horizontal',
                        'id' => 'video-1'
                    ]) !!}

                    {!! Form::hidden('product_id', 5, array('id' => 'product_id')) !!}

                    <div class="form-group" id="video_campaign_objective">
                        {!! Form::label('video_campaign_objective', 'Campaign Objective', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('video_campaign_objective', $drm_video_campaign_objectives, $drm_objective, array('class' => 'form-control') ) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="e.g. Awareness, Consideration, Action"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <h4>Primary Campaign KPI</h4>

                    <div class="form-group" id="video_primary_campaign_metric">
                        {!! Form::label('video_primary_campaign_metric', 'Primary Campaign Metric', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('video_primary_campaign_metric', $vod_primary_metric, array('class' => 'form-control')) !!}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Compulsary field, see KPI Definitions tab"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="video_primary_metric_value">
                        {!! Form::label('video_primary_metric_value', 'Metric Value', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('video_primary_metric_value', $vod_primary_metric_value, array('class' => 'form-control')) !!}
{{--                            {!! Form::select('video_primary_metric_value', $video_primary_metrics, $vod_primary_metric_value, array('class' => 'form-control') ) !!}--}}
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Compulsary field, see KPI Definitions tab"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <h4>Secondary Campaign KPI</h4>

                    <div class="form-group">
                        {!! Form::label('video_secondary_campaign_metric', 'Secondary Campaign Metric', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('video_secondary_campaign_metric', $vod_secondary_metric, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('video_secondary_metric_value', 'Secondary Value', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('video_secondary_metric_value', $vod_secondary_metric_value, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="video-1-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--video part 2--}}
                <div class="sliding-content" id="video-2">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Programmatic Video 2</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'video-2',
                        'class' => 'form-horizontal',
                        'id' => 'video-2'
                    ]) !!}

                    {!! Form::hidden('product_id', 5, array('id' => 'product_id')) !!}

                    <div class="form-group" id="video_geo_targeting">
                        {!! Form::label('video_geo_targeting', 'Geo Targeting', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::select('video_geo_targeting', $geo_targeting, $vod_geo_value, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <h4>Demographic Reporting</h4>

                    <div class="form-group">
                        {!! Form::label('video_demo_target', 'Demo Target', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('video_demo_target', $vod_demo_target, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group" id="video_inventory_screen_types">
                        {!! Form::label('video_inventory_screen_types[]', 'Inventory/Screen Types', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            @foreach ($video_inventory_screentypes as $video_inventory_screentype)
                                @php
                                    $selected = false;
                                    if(count($vod_inventory) > 0){
                                        if(array_key_exists($video_inventory_screentype,$vod_inventory)){
                                            if($vod_inventory[$video_inventory_screentype] == 'Y'){
                                                $selected = true;
                                            }
                                        }
                                    }
                                @endphp

                                <label class="checkbox-inline">
                                    {!! Form::checkbox('video_inventory_screen_types[]', $video_inventory_screentype, $selected) !!} {{ $video_inventory_screentype }}
                                </label>
                            @endforeach
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Sky Desktop Short Form is the only broadcaster inventory available at this time"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="video_creative_lengths">
                        {!! Form::label('video_creative_lengths[]', 'Available Copy Length', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            @foreach ($creative_considerations as $creative_consideration)
                                @php
                                    $selected = false;
                                    if(count($vod_copy_length) > 0){
                                        if(in_array($creative_consideration, $vod_copy_length)){
                                            $selected = true;
                                        }
                                    }
                                @endphp

                                <label class="checkbox-inline">
                                    {!! Form::checkbox('video_creative_lengths[]', $creative_consideration, $selected) !!} {{ $creative_consideration }}
                                </label>
                            @endforeach
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Please select the creative length(s) available"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group" id="video_creative_types">
                        {!! Form::label('video_creative_types[]', 'Video Creative Type', array('class' => 'required col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            @foreach ($video_creative_types as $video_creative_type)
                                @php
                                    $options = [];
                                    $selected = false;
                                    if(count($vod_creative_type) > 0){
                                        if(in_array($video_creative_type, $vod_creative_type)){
                                            $selected = true;
                                        }
                                    }
                                @endphp

                                @if($video_creative_type == 'In Stream')
                                    @php $options = ['disabled' => 'disabled']; @endphp
                                @endif

                                <label class="checkbox-inline">
                                    {!! Form::checkbox('video_creative_types[]', $video_creative_type, $selected, $options) !!} {{ $video_creative_type }}
                                </label>
                            @endforeach
                        </div>
                        <div class="help-text col-sm-1"><button type="button" class="btn btn-default btn-sm" data-toggle="popover" data-trigger="focus" data-container="body" data-content="Recommend VAST AND VPAID formats. Inventory is restricted with VPAID only creatives"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></button></div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('interactive_creative_provider', 'Interactive Creative Provider', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('interactive_creative_provider', $vod_interactive_provider, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="video-2-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save and proceed <i class="glyphicon glyphicon-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @else
                        @if( $status_id >= \App\Status::BRIEF_SUBMITTED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--additional info/file upload--}}
                <div class="sliding-content" id="additional-info">
                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Additional Info</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {!! Form::open([
                        'route' => 'brief-file-upload',
                        'class' => 'form-horizontal',
                        'id' => 'file-upload'
                    ]) !!}

                    <div class="form-group">
                        {!! Form::label('additional_notes', 'Additional Notes', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::textarea('additional_notes', $additional_info, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('deadline', 'Deadline for brief response', array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-6">
                            {!! Form::text('deadline', $brief_response_deadline, array('class' => 'form-control', 'id' => 'deadline')) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        {!! Form::label('additional_files[]', 'Files', array('class' => 'col-sm-3 control-label')) !!}
                    </div>
                    @foreach($number_of_brief_file_fields as $field_number)
                        <div class="form-group">
                            @php $upload_visiblity = ''; @endphp
                            @if(array_key_exists($field_number, $existing_brief_files_arr))
                                @php $upload_visiblity = 'display: none;'; @endphp
                                <div class="col-md-offset-3 col-sm-6" id="existing-brief-file-{{ $field_number }}">
                                    <p><a target="_blank" href="{{ Storage::disk('public')->url($existing_brief_files_arr[$field_number]['location']) }}">{{ $existing_brief_files_arr[$field_number]['file_name'] }}</a></p>

                                    @if(in_array($status_id, array(\App\Status::NEW_BRIEF, null)))
                                        <p><a href="#"  id="edit-brief-file-{{ $field_number }}" class="edit-brief-file">Edit</a></p>
                                    @endif
                                </div>
                            @endif

                            <div id="upload-brief-file-{{ $field_number }}" style="{{ $upload_visiblity }}">

                                @php
                                    $brief_file_number = $field_number+1;
                                    $label = 'File '.$brief_file_number;
                                @endphp
                                {{--{!! Form::label('additional_files[]', $label, array('class' => 'col-sm-3 control-label')) !!}--}}
                                <div class="col-md-offset-3 col-sm-6">
                                    {!! Form::file('additional_files[]', array('id' => 'file-upload-'.$field_number, 'class' => 'file form-control', 'data-show-upload' => 'false', 'data-show-preview' => 'false')) !!}
                                </div>
                                @if(array_key_exists($field_number, $existing_brief_files_arr)) <p><a href="#" id="cancel-brief-file-{{$field_number}}" class="cancel-brief-file">Cancel</a></p> @endif
                            </div>

                        </div>
                    @endforeach

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button id="file-upload-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default">
                                        Save
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>

                    {{--only agency and dev users can access submit brief panel--}}
                    @if(\Baselib::canCreateBrief())
                        @if($status_id >= \App\Status::TARGETING_GRID_APPROVED)
                            <a class="btn btn-default next-button btn-sm" href="#" role="button">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            </a>
                        @endif
                    @endif
                </div>

                {{--submit panel, only accessible to agency and dev users--}}
                @if(\Baselib::canCreateBrief())
                    <div class="sliding-content" id="submit-brief">

                    <div class="row vertical-align-center">
                        <div class="col-md-6">
                            <h3 class="med-margin-bottom">Submit Brief</h3>
                        </div>
                        <div class="col-md-6">
                            @if ($campaign != null)
                                <div class="btn-toolbar">
                                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('cancel-campaign-form', ['campaign_id'=>$campaign->id]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                                </div>
                            @endif
                        </div>
                    </div>


                    {!! Form::open([
                        'route' => 'submit-brief',
                        'class' => 'form-horizontal',
                        'id' => 'submit-brief'
                    ]) !!}

                    @if($status_id < \App\Status::TARGETING_GRID_APPROVED)
                        <!-- Add Buttons -->
                        @if(\Baselib::canCreateBrief())
                            <div class="form-group">
                                <div class="col-sm-12 text-center">
                                    <button id="brief-submit" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default btn-lg">
                                        Submit Brief
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif

                    {!! Form::close() !!}

                    <a class="btn btn-default back-button btn-sm" href="#" role="button">
                        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                </div>
                @endif

            </div>
        </div>
    </div>
