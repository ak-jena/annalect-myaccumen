@php
    //set up default values
    $date_change_comment = null;
        
    //set up default values
    $display_amazon_budget          = 0;
    $display_appnexus_budget        = 0;
    $display_the_tradedesk_budget   = 0;
    $display_dbm_budget             = 0;
    $display_brightroll_budget      = 0;
    $display_total                  = number_format(0.00, 2);


    $mobile_the_tradedesk_budget    = 0;
    $mobile_appnexus_budget         = 0;
    $mobile_strikead_budget         = 0;
    $mobile_adelphic_budget         = 0;
    $mobile_brightroll_budget       = 0;
    $mobile_dbm_budget              = 0;
    $mobile_total                   = number_format(0.00, 2);


    $rich_media_dbm_budget              = 0;
    $rich_media_the_tradedesk_budget    = 0;
    $rich_media_appnexus_budget         = 0;
    $rich_media_total                   = number_format(0.00, 2);

    $audio_adwizz_budget            = 0;
    $audio_the_tradedesk_budget     = 0;
    $audio_appnexus_budget          = 0;
    $audio_total                    = number_format(0.00, 2);

    $vod_tube_mogul_budget      = 0;
    $vod_aol_budget             = 0;
    $vod_dbm_budget_budget      = 0;
    $vod_amazon_budget          = 0;
    $vod_the_tradedesk_budget   = 0;
    $vod_videology_budget       = 0;
    $vod_dbm_budget             = 0;
    $vod_brightroll_budget      = 0;
    $vod_total                  = number_format(0.00, 2);

    $total_budget = number_format(0.00, 2);

    $display_booking    = null;
    $mobile_booking     = null;
    $rich_media_booking = null;
    $audio_booking      = null;
    $vod_booking        = null;

    $google_audiences = array(
        'YouTube' => 'YouTube',
        'Google Analyticis' => 'Google Analytics',
        'DCM' => 'DCM',
        'Search' => 'Search'
    );

    $is_stack_client = 0;
    $selected_google_audiences = array();

    $booking_file_name = null;
    $booking_file_location = null;

    $planned_display_budget = number_format(0.00, 2);
    $planned_mobile_budget = number_format(0.00, 2);
    $planned_rich_media_budget = number_format(0.00, 2);
    $planned_vod_budget = number_format(0.00, 2);
    $planned_audio_budget = number_format(0.00, 2);
@endphp

@if($campaign !== null)
    @php
        $booking_file_name = $campaign->brief->file_name;
        $booking_file_location = $campaign->brief->location;

        $all_comments = $campaign->brief->comments()->where('title', 'Brief start/end dates changed')->get();

        if($all_comments->count() > 0){
            $date_change_comment = $all_comments->sortByDesc('created_at')->first();
        }

        // display, rich media and mobile dps budget fields belong to one panel so we need to handle the panel display differently
        $all_products       = $campaign->products;
        $drm_products       = $campaign->products()->whereIn('id', array(App\Product::DISPLAY, App\Product::RICH_MEDIA, App\Product::MOBILE ))->get();
        $non_drm_products   = $campaign->products()->whereIn('id', array(App\Product::AUDIO, App\Product::VOD))->get();

        foreach ($drm_products as $drm_product){
            switch ($drm_product->name) {
                case 'Display':
                    $planned_display_budget = number_format($drm_product->pivot->budget, 2);
                    break;
                case 'Rich Media':
                    $planned_rich_media_budget = number_format($drm_product->pivot->budget, 2);
                    break;
                case 'Mobile':
                    $planned_mobile_budget = number_format($drm_product->pivot->budget, 2);
                    break;
                default:
                    // not needed
                    break;
            }
        }

        foreach ($non_drm_products as $non_drm_product){
            switch ($non_drm_product->name) {
                case 'Audio':
                    $planned_audio_budget = number_format($non_drm_product->pivot->budget, 2);
                    break;
                case 'VOD':
                    $planned_vod_budget = number_format($non_drm_product->pivot->budget, 2);
                    break;
                default:
                    // not needed
                    break;
            }
        }

        // retrieve existing
        if($booking_details !== null){
            $display_booking = $booking_details->where('product_id', App\Product::DISPLAY)->first();
            $mobile_booking = $booking_details->where('product_id', App\Product::MOBILE)->first();
            $rich_media_booking = $booking_details->where('product_id', App\Product::RICH_MEDIA)->first();
            $audio_booking = $booking_details->where('product_id', App\Product::AUDIO)->first();
            $vod_booking = $booking_details->where('product_id', App\Product::VOD)->first();

            if(count($display_booking) > 0){
                // retrieve existing budget values
                $display_amazon_budget          = $display_booking->dspBudgets()->where('dsp_id', App\Dsp::AMAZON)->value('budget') ?:  0;
                $display_appnexus_budget        = $display_booking->dspBudgets()->where('dsp_id', App\Dsp::APPNEXUS)->value('budget') ?:  0;
                $display_the_tradedesk_budget   = $display_booking->dspBudgets()->where('dsp_id', App\Dsp::TRADEDESK)->value('budget') ?:  0;
                $display_dbm_budget             = $display_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM)->value('budget') ?:  0;
                $display_brightroll_budget      = $display_booking->dspBudgets()->where('dsp_id', \App\Dsp::BRIGHTROLL)->value('budget') ?:  0;


                $display_total = round($display_amazon_budget + $display_appnexus_budget + $display_the_tradedesk_budget + $display_dbm_budget + $display_brightroll_budget, 2);
            }

            if(count($mobile_booking) > 0){
                $mobile_the_tradedesk_budget    = $mobile_booking->dspBudgets()->where('dsp_id', App\Dsp::TRADEDESK)->value('budget') ?:  0;
                $mobile_appnexus_budget         = $mobile_booking->dspBudgets()->where('dsp_id', App\Dsp::APPNEXUS)->value('budget') ?:  0;
                $mobile_strikead_budget         = $mobile_booking->dspBudgets()->where('dsp_id', App\Dsp::STRIKEAD)->value('budget') ?:  0;
                $mobile_adelphic_budget         = $mobile_booking->dspBudgets()->where('dsp_id', App\Dsp::ADELPHIC)->value('budget') ?:  0;
                $mobile_brightroll_budget       = $mobile_booking->dspBudgets()->where('dsp_id', \App\Dsp::BRIGHTROLL)->value('budget') ?:  0;
                $mobile_dbm_budget              = $mobile_booking->dspBudgets()->where('dsp_id', \App\Dsp::DBM)->value('budget') ?:  0;

                $mobile_total = round($mobile_the_tradedesk_budget + $mobile_appnexus_budget + $mobile_strikead_budget + $mobile_adelphic_budget + $mobile_brightroll_budget + $mobile_dbm_budget, 2);
            }

            if(count($rich_media_booking) > 0){
                $rich_media_dbm_budget     = $rich_media_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM)->value('budget') ?:  0;
                $rich_media_the_tradedesk_budget    = $rich_media_booking->dspBudgets()->where('dsp_id', App\Dsp::TRADEDESK)->value('budget') ?:  0;
                $rich_media_appnexus_budget         = $rich_media_booking->dspBudgets()->where('dsp_id', App\Dsp::APPNEXUS)->value('budget') ?:  0;

                $rich_media_total = round($rich_media_dbm_budget + $rich_media_the_tradedesk_budget + $rich_media_appnexus_budget, 2);
            }

            if(count($audio_booking) > 0){
                $audio_adwizz_budget      = $audio_booking->dspBudgets()->where('dsp_id', App\Dsp::ADSWHIZZ)->value('budget') ?:  0;

                $audio_total = round($audio_adwizz_budget, 2);
            }

            if(count($vod_booking) > 0){
                $vod_tube_mogul_budget          = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::TUBE_MOGUL)->value('budget') ?:  0;
                $vod_aol_budget                 = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::AOL)->value('budget') ?:  0;
                $vod_dbm_budget_budget          = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM_TV)->value('budget') ?:  0;
                $vod_amazon_budget              = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::AMAZON)->value('budget') ?:  0;
                $vod_the_tradedesk_budget       = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::TRADEDESK)->value('budget') ?:  0;
                $vod_videology_budget           = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::VIDEOLOGY)->value('budget') ?:  0;
                $vod_dbm_budget                 = $vod_booking->dspBudgets()->where('dsp_id', App\Dsp::DBM)->value('budget') ?:  0;
                $vod_brightroll_budget          = $vod_booking->dspBudgets()->where('dsp_id', \App\Dsp::BRIGHTROLL)->value('budget') ?:  0;

                $vod_total = round($vod_tube_mogul_budget + $vod_aol_budget + $vod_dbm_budget_budget + $vod_amazon_budget + $vod_the_tradedesk_budget + $vod_videology_budget + $vod_dbm_budget + $vod_brightroll_budget, 2);
            }

            $total_budget = round($display_total + $mobile_total + $rich_media_total + $audio_total + $vod_total, 2);

            // misc info defaults
            $is_stack_client = $campaign->brief->is_stack_client;
            $selected_google_audiences = json_decode($campaign->brief->google_audiences);

            $booking_file_name = $campaign->brief->file_name;
            $booking_file_location = $campaign->brief->location;
        }

        $booking_form_op = 'Add';
        if($campaign->getAllBookingsStatus()->id == \App\BookingStatus::SUBMITTED ){
            $booking_form_op = 'View';
        }
    @endphp

    <div class="row vertical-align-center">
        <div class="col-md-3 col-md-offset-9">
            @if ($campaign != null)
                <div class="btn-toolbar">
                    {{--<a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>--}}

                    <a style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('comments', ['brief_id'=>$campaign->brief->id, 'redirect' => 'workflow' ]) }}"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span></a>
                    @if($status_id >= \App\Status::BOOKING_FORM_SUBMITTED)
                        <a title="Export Booking" style="margin-top: 20px;" class="pull-right btn btn-default med-margin-bottom" href="{{ route('export-booking', ['brief_id'=>$campaign->brief->id]) }}"><span class="glyphicon glyphicon-export" aria-hidden="true"></span></a>
                    @endif
                </div>
            @endif
        </div>
    </div>


    <div id="budget-success" class="alert alert-success alert-dismissible" style="display:none;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        DSP Budget Data saved successfully.
    </div>

    <div id="date-success" class="alert alert-success alert-dismissible" style="display:none;" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        Date changed successfully.
    </div>

    <div class="alert alert-danger alert-dismissible" id="dsp-errors-alert" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="fa fa-ban"></i> Error detected:</h4>
    </div>


    {!! Form::open([
        'class' => 'form-horizontal',
        'id' => 'date_change_form'
    ]) !!}
        <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

        <div class="row small-margin-bottom">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Date</h3>
                    </div>
                    <div class="panel-body">

                        <div class="form-group" id="campaign_dates">
                            {!! Form::label('edit_campaign_dates', 'Start/End Dates', array('class' => 'col-sm-3 control-label')) !!}
                            <div class="col-sm-3">
                                {!! Form::text('edit_campaign_dates', $dates, array('class' => 'form-control', 'id' => 'edit_campaign_dates')) !!}
                            </div>
                            {!! Form::label('date_change_reason', 'Reason for change', array('class' => 'col-sm-2 control-label')) !!}
                            <div class="col-sm-3">
                                @if($date_change_comment != null)
                                    @php $date_change_reason = preg_replace('!\s+!', ' ',strip_tags($date_change_comment->body)); @endphp
                                @else
                                    @php $date_change_reason = ''; @endphp
                                @endif
                                {!! Form::textarea('date_change_reason', $date_change_reason, array('class' => 'form-control', 'id' => 'date_change_reason', 'cols'=> '35', 'rows' => '7')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::CAMPAIGN_LIVE)))
                                    @if(\Baselib::canCreateBooking())
                                        <button id="date-change" value="date-change" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default dsp-submit">
                                            Save
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

    {!! Form::open([
        'class' => 'form-horizontal',
        'id' => 'dsps_budgets_form'
    ]) !!}
        <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">
        <div class="row small-margin-bottom">
            <div class="col-md-10 col-md-offset-1">
                <div class="alert alert-warning" role="alert">
                    Please ensure all ASBOF, Agency commission, adserving etc is taken out before giving us the Agency NET Budget
                </div>
            </div>
        </div>

            @if(count($drm_products) > 0)
                <div class="row small-margin-bottom">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Display, Rich Media and Mobile</h3>
                            </div>
                            <div class="panel-body">
                                @foreach ($drm_products as $product)
                                    @php
                                        $product_name = strtolower($product->name);
                                        $formatted_product_name = str_replace(' ','_', $product_name);
                                    @endphp

                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <span class="h4">{{ $product->name }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            @if($all_products->count() > 1)
                                                @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED, \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::CAMPAIGN_LIVE)))
                                                    @if(\Baselib::canCreateBooking())
                                                        <button id="discard-{{ $product_name }}" value="{{ $product_name }}" type="button" data-spinner-color="#33cc33" data-style="zoom-in" class="pull-right ladda-button btn btn-default discard">
                                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                        </button>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>

                                    </div>


                                    <div class="form-group">
                                        @foreach ($product->dsps as $dsp)
                                            @php
                                                $dsp_name = $dsp->dsp_name;
                                                $formatted_dsp_name = str_replace(' ','_', strtolower($dsp_name));
                                                $formatted_dsp_name = str_replace(array('(',')'),'', $formatted_dsp_name);
                                            @endphp

                                            {!! Form::label($formatted_product_name.'['.$dsp->id.']', ucfirst($dsp_name).' Budget', array('class' => 'col-sm-3 control-label')) !!}
                                            <div class="col-sm-2">
                                                <div class="input-group">
                                                    <span class="input-group-addon">£</span>
                                                    @php
                                                        $dsp_budget_value = ${$formatted_product_name.'_'.$formatted_dsp_name.'_budget'};
                                                    @endphp

                                                    @if($dsp_budget_value <= 0)
                                                        @php
                                                            $dsp_budget_value = '';
                                                        @endphp
                                                    @endif
                                                    {!! Form::text('dsp_data['.$formatted_product_name.']['.$dsp->id.']', $dsp_budget_value, array('id' => $formatted_product_name.'_dsp_budget_'.$formatted_dsp_name, 'class' => 'form-control')) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label($formatted_product_name.'_planned_budget', 'Planned '.ucfirst($product_name).' Budget', array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-2">
                                            <p class="form-control-static">
                                                &pound;@php echo ${'planned_'.$formatted_product_name.'_budget'}; @endphp
                                            </p>
                                        </div>

                                        {!! Form::label($formatted_product_name.'_total_budget', ucfirst($product_name).' Total', array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <span class="input-group-addon">£</span>
                                                {!! Form::text($formatted_product_name.'_total_budget', number_format(${$formatted_product_name.'_total'},2,'.',''), array('class' => 'form-control', 'readonly')) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED, \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::CAMPAIGN_LIVE)))
                                            @if(\Baselib::canCreateBooking())
                                                <button id="submit-dsps-{{ $formatted_product_name }}" value="save-dsp" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default dsp-submit">
                                                    Save DSP Budget's Data
                                                </button>
                                            @endif
                                        @endif

                                        <a id="{{ $formatted_product_name }}-booking" class="btn btn-default @if(${$formatted_product_name.'_booking'} == null) disabled @endif" href="{{ URL::route('booking', [$campaign_id, $campaign->getMediaMobileDisplayProductIds()]) }}" role="button"><span class="glyphicon glyphicon-pencil"></span> {{ $booking_form_op }} Booking
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif {{--drm panel display--}}

            @foreach ($non_drm_products as $product)
                {{--display, rich media and mobile dsp budget fields are all in one panel, the rest are in a panel of their own--}}

                @php
                    $product_name = strtolower($product->name);
                    $formatted_product_name = str_replace(' ','_', $product_name);
                @endphp
                <div class="row small-margin-bottom">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{ ucfirst($product_name) }}</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        @if($all_products->count() > 1)
                                            @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED, \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::CAMPAIGN_LIVE)))
                                                @if(\Baselib::canCreateBooking())
                                                    <button id="discard-{{ $product_name }}" value="{{ $product_name }}" type="button" data-spinner-color="#33cc33" data-style="zoom-in" class="pull-right ladda-button btn btn-default discard">
                                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                    </button>
                                                @endif
                                            @endif
                                        @endif
                                    </div>

                                </div>
                                <div class="form-group">
                                    @foreach ($product->dsps as $dsp)
                                        @php
                                            $dsp_name = $dsp->dsp_name;
                                            $formatted_dsp_name = str_replace(' ','_', strtolower($dsp_name));
                                            $formatted_dsp_name = str_replace(array('(',')'),'', $formatted_dsp_name);
                                        @endphp

                                        {!! Form::label($formatted_product_name.'['.$dsp->id.']', ucfirst($dsp_name).' Budget', array('class' => 'col-sm-3 control-label')) !!}
                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                @php
                                                    $dsp_budget_value = ${$formatted_product_name.'_'.$formatted_dsp_name.'_budget'};
                                                @endphp

                                                @if($dsp_budget_value <= 0)
                                                    @php
                                                        $dsp_budget_value = '';
                                                    @endphp
                                                @endif
                                                <span class="input-group-addon">£</span>
                                                {!! Form::text('dsp_data['.$formatted_product_name.']['.$dsp->id.']', $dsp_budget_value, array('id' => $formatted_product_name.'_dsp_budget_'.$formatted_dsp_name, 'class' => 'form-control')) !!}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group">
                                    {!! Form::label($formatted_product_name.'_planned_budget', 'Planned '.ucfirst($product_name).' Budget', array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-2">
                                        <p class="form-control-static">
                                            &pound;@php echo ${'planned_'.$formatted_product_name.'_budget'}; @endphp
                                        </p>
                                    </div>

                                    {!! Form::label($formatted_product_name.'_total_budget', ucfirst($product_name).' Total', array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <span class="input-group-addon">£</span>
                                            {!! Form::text($formatted_product_name.'_total_budget', number_format(${$formatted_product_name.'_total'},2,'.',''), array('class' => 'form-control', 'readonly')) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED,  \App\Status::BF_APPROVED_BY_ACT_LINE_MANAGER, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER, \App\Status::ADDED_IO_HOST_LINKS, \App\Status::IO_UPLOADED, \App\Status::UPLOADED_CREATIVE_TAGS, \App\Status::CAMPAIGN_LIVE)))

                                            @if(\Baselib::canCreateBooking())
                                                <button id="submit-dsps-{{ $formatted_product_name }}" value="save-dsp" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default dsp-submit">
                                                    Save {{ ucfirst($product_name) }}'s DSP Budget's Data
                                                </button>
                                            @endif
                                        @endif
                                        <a id="{{ $formatted_product_name }}-booking" class="btn btn-default @if(${$formatted_product_name.'_booking'} == null) disabled @endif" href="{{ url('/booking', [$campaign_id, $product->id]) }}" role="button"><span class="glyphicon glyphicon-pencil"></span> {{ $booking_form_op }} {{ ucfirst($product_name) }} Booking
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

            <div class="row small-margin-bottom">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Misc Info</h3>
                        </div>

                        <div class="panel-body">
                            <div class="form-group">
                                {!! Form::label('is_stack_client', 'Is it a Google stack client?', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        {!! Form::select('is_stack_client', [0 => 'No', 1 => 'Yes'], $is_stack_client, array('class' => 'form-control')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('google_audiences', 'If yes, what Google Audiences are to be activated?', array('class' => 'col-sm-3 control-label')) !!}

                                <div class="col-sm-6">

                                    @foreach($google_audiences as $google_audience)
                                        @php $selected = false; @endphp
                                        @if($selected_google_audiences !== null)
                                            @if(in_array($google_audience, $selected_google_audiences))
                                                @php $selected = true; @endphp
                                            @endif
                                        @endif
                                        <label class="checkbox-inline">
                                            {!! Form::checkbox('google_audiences[]', $google_audience, $selected) !!} {{ $google_audience }}
                                        </label>
                                    @endforeach
                                </div>

                            </div>

                            @php $upload_visiblity = ''; @endphp
                            <div class="form-group">
                                @if($booking_file_name !== null && $booking_file_location !== null)
                                    @php $upload_visiblity = 'display: none;'; @endphp
                                    <div class="col-md-offset-3 col-sm-6" id="existing-booking-file">
                                        <p><a href="{{ Storage::disk('public')->url($booking_file_location) }}">{{ $booking_file_name }}</a></p>

                                        @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED)))
                                            <p><a href="#" id="edit-booking-file" class="edit-booking-file">Edit</a></p>
                                        @endif
                                    </div>
                                @endif

                                <div id="upload-booking-file" style="{{ $upload_visiblity }}">
                                    {!! Form::label('file', 'File Upload', array('class' => 'col-sm-3 control-label')) !!}
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            {!! Form::file('file', null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    @if($booking_file_name !== null && $booking_file_location !== null) <p><a href="#" id="cancel-booking-file">Cancel</a></p> @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    {{--prevent booking from edited after it has been submitted--}}
                                    @if($status_id >= \App\Status::TARGETING_GRID_APPROVED && $status_id <= \App\Status::UPLOADED_CREATIVE_TAGS )
                                        @if(\Baselib::canCreateBooking())
                                            <button id="submit-misc-info" value="save-misc" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default dsp-submit">
                                                Save
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row small-margin-bottom">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="form-group form-group-lg">
                                {!! Form::label('booking_total_budget', 'Total Budget', array('class' => 'col-sm-3 control-label')) !!}
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <span class="input-group-addon">£</span>
                                        {!! Form::text('booking_total_budget', number_format($total_budget,2,'.',''), array('class' => 'form-control', 'readonly')) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-offset-3 col-md-6">
                                    @if($campaign->getAllBookingsStatus()->id == \App\BookingStatus::SUBMITTED )
                                        @if(in_array($status_id, array(\App\Status::TARGETING_GRID_APPROVED, \App\Status::BF_REJECTED_BY_ACT_TEAM, \App\Status::BF_REJECTED_BY_ACT_LINE_MANAGER/*, \App\Status::IO_UPLOADED, \App\Status::ADDED_IO_HOST_LINKS*/)))
                                            @if(\Baselib::canCreateBooking())
                                                <button id="submit-booking" value="submit-booking" type="submit" data-spinner-color="#33cc33" data-style="zoom-in" class="ladda-button btn btn-default btn-lg">
                                                    Submit Booking
                                                </button>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    {!! Form::close() !!}

    @if(\Baselib::canApproveBooking())
        {!! Form::open([
                'route' => 'booking-approval',
                'class' => 'form-horizontal',
                'id'    => 'booking_approval_form',
        ]) !!}
        <div class="col-sm-offset-1 col-sm-11">
            <input type="hidden" name="campaign_id" value="{{ $campaign_id }}">

            {{--check status of campaign, if booking has been submitted--}}
            @if($status_id == \App\Status::BOOKING_FORM_SUBMITTED)
                @if(\Baselib::isActivationUser() || \Baselib::isVodUser())
                    <button type="submit" class="btn btn-default" name="booking" value="at-approve-booking">
                        <i class="fa fa-plus"></i> Approve Booking (AT)
                    </button>

                    {{--<button type="submit" class="btn btn-default" name="booking" value="at-reject-booking">--}}
                        {{--<i class="fa fa-minus"></i> Reject Booking (AT)--}}
                    {{--</button>--}}
                    <a class="btn btn-default" href="{{ route('reject-bf', ['campaign_id' => $campaign->id]) }}" role="button">Reject Booking Form</a>
                @elseif(\Baselib::isActivationLineManager())
                    <button type="submit" class="btn btn-default" name="booking" value="lm-approve-booking">
                        <i class="fa fa-plus"></i> Approve Booking (LM)
                    </button>

                    {{--<button type="submit" class="btn btn-default" name="booking" value="lm-reject-booking">--}}
                        {{--<i class="fa fa-minus"></i> Reject Booking (LM)--}}
                    {{--</button>--}}
                    <a class="btn btn-default" href="{{ route('reject-bf', ['campaign_id' => $campaign->id]) }}" role="button">Reject Booking Form</a>
                @endif

                {{--check user role--}}
                {{--show relevant approval/rejection buttons--}}
            @elseif($status_id == \App\Status::BF_APPROVED_BY_ACT_TEAM)
                @if(\Baselib::isActivationLineManager() || \Baselib::isVodUser())
                    <button type="submit" class="btn btn-default" name="booking" value="lm-approve-booking">
                        <i class="fa fa-plus"></i> Approve Booking (LM)
                    </button>

                    {{--<button type="submit" class="btn btn-default" name="booking" value="lm-reject-booking">--}}
                        {{--<i class="fa fa-minus"></i> Reject Booking (LM)--}}
                    {{--</button>--}}
                    <a class="btn btn-default" href="{{ route('reject-bf', ['campaign_id' => $campaign->id]) }}" role="button">Reject Booking Form</a>
                @endif
            @endif

        </div>
        {!! Form::close() !!}

    @endif
@endif {{--campaign null check--}}
