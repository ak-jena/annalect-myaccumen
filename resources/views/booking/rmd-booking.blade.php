@section('subtitle','Rich Media, Mobile and Display')

@php
$rich_media_creative_formats_1 = array(
    'Lighbox - Scoota'             => 'Lighbox - Scoota',
    'Page Shell - Scoota'           => 'Page Shell - Scoota',
    'Parallax - Scoota'             => 'Parallax - Scoota',
    'Interactive Lightbox - Scoota' => 'Interactive Lightbox - Scoota',
    'Interactive Expand - Scoota'   => 'Interactive Expand - Scoota',
    'Interactive Mobile Expand - Scoota' =>'Interactive Mobile Expand - Scoota',
    'Interactive Display - Scoota' => 'Interactive Display - Scoota',
    'Shutter - Scoota' => 'Shutter - Scoota'
);

$rich_media_creative_formats_2 = array(
    'Classic Skinz - Sublime Skinz (Adylic Build)' => 'Classic Skinz - Sublime Skinz (Adylic Build)',
    'Wingz - Sublime Skinz (Adylic Build)' => 'Wingz - Sublime Skinz (Adylic Build)',
    'Video Skinz - Sublime Skinz (Adylic Build)' => 'Video Skinz - Sublime Skinz (Adylic Build)',
    'Video Skinz Billboard - Sublime Skinz (Adylic Build)' => 'Video Skinz Billboard - Sublime Skinz (Adylic Build)',
    'Video Classic - Sublime Skinz (Adylic Build)' => 'Video Classic - Sublime Skinz (Adylic Build)',
    'Skin Bill - Sublime Skinz (Adylic Build)' => 'Skin Bill - Sublime Skinz (Adylic Build)',
    'Shopping Skinz - Sublime Skinz (Adylic Build)' => 'Shopping Skinz - Sublime Skinz (Adylic Build)',
    'Billboard Skinz - Sublime Skinz (Adylic Build)' => 'Billboard Skinz - Sublime Skinz (Adylic Build)'
);

$rich_media_creative_formats_3 = array(
    'Classic Skinz - Sublime Skinz' => 'Classic Skinz - Sublime Skinz',
    'Wingz - Sublime Skinz' => 'Wingz - Sublime Skinz',
    'Video Skinz - Sublime Skinz' => 'Video Skinz - Sublime Skinz',
    'Video Skinz Billboard - Sublime Skinz' => 'Video Skinz Billboard - Sublime Skinz',
    'Video Classic - Sublime Skinz' => 'Video Classic - Sublime Skinz',
    'Skin Bill - Sublime Skinz' => 'Skin Bill - Sublime Skinz',
    'Shopping Skinz - Sublime Skinz' => 'Shopping Skinz - Sublime Skinz',
    'Billboard Skinz - Sublime Skinz' => 'Billboard Skinz - Sublime Skinz'
);

$rich_media_creative_formats_4 = array(
    'Brand Expandable - Collective' => 'Brand Expandable - Collective',
    'Brand Takeover - Collective' => 'Brand Takeover - Collective',
    'Brand Rise - Collective' => 'Brand Rise - Collective',
    'Brand Skins - Collective' => 'Brand Skins - Collective'
);

$rich_media_creative_formats_5 = array(
    'Other' => 'Other',
    'Not Applicable' => 'Not Applicable'
);


$adserver = array('DCM' => 'DCM', 'Flashtalking' => 'Flashtalking', 'Sizmek' => 'Sizmek', 'Celtra' => 'Celtra', 'Spongecell' => 'Spongecell', 'Atlas' => 'Atlas', 'Other' => 'Other');

$adserver_select_val            = '';
$adserver_other_visibility      = 'style=display:none;';
$adserver_other_val             = '';

$rm_creative_format_select_val  = '';
$rm_creative_other_visibility   = 'style=display:none;';
$rm_creative_format_other_val   = '';
$rm_creative_notes = null;
@endphp

@if($booking_detail !== null)
    @if($booking_detail->rm_creative_format !== null)
        @if(in_array('Other', json_decode($booking_detail->rm_creative_format)))
            @php
                $rm_creative_other_visibility = '';
                $rm_creative_format_other_val = $booking_detail->rm_creative_format_other;
            @endphp
        @endif
    @endif

    @if(!in_array($booking_detail->adserver, $adserver))
        @php
            $adserver_other_visibility = '';
            $adserver_other_val = $booking_detail->adserver;
            $adserver_select_val = 'Other';
        @endphp
    @else
        @php $adserver_select_val = $booking_detail->adserver; @endphp
    @endif

    @if($booking_detail->pricing_model == NULL)
        @php
            $pricing_model = 'Variable CPM';
        @endphp
    @else
        @php
            $pricing_model = $booking_detail->pricing_model;
        @endphp
    @endif

    @if($booking_detail->has_budget_silos == null)
        @php
            $has_budget_silos = 0;
        @endphp
    @else
        @php
            $has_budget_silos = $booking_detail->has_budget_silos;
        @endphp
    @endif

    @if($booking_detail->specific_activity_tags == NULL)
        @php
            $specific_activity_tags = 0;
        @endphp
    @else
        @php
            $specific_activity_tags = $booking_detail->specific_activity_tags;
        @endphp
    @endif

    @if($booking_detail->is_1x1_supplied == NULL)
        @php
            $is_1x1_supplied = 0;
        @endphp
    @else
        @php
            $is_1x1_supplied = $booking_detail->is_1x1_supplied;
        @endphp
    @endif
    @if($booking_detail->rm_creative_notes !== NULL)
        @php
            $rm_creative_notes = $booking_detail->rm_creative_notes;
        @endphp
    @endif


@else
    @php
        $pricing_model = 'Variable CPM';
        $has_budget_silos = 0;
        $specific_activity_tags = 0;
        $is_1x1_supplied = 0;
    @endphp
@endif


{{--@php var_dump($errors->keys()) @endphp--}}
<div class="box box-info">
    {!! Form::model(
        $booking_detail,
        [
            'route' => ['process-booking', $campaign->id, $campaign->getMediaMobileDisplayProductIds()]
        ])
    !!}

    <div class="box-header">
        @include('partials.alerts.errors')
    </div>
    <div class="box-body">
        <legend>Campaign Booking Information</legend>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('pricing_model', 'Pricing model:', ['class' => 'control-label']) !!}
                {!! Form::text('pricing_model', $pricing_model, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('has_budget_silos', 'Is Budget Silos?', ['class' => 'required control-label']) !!}<br>
                {!! Form::select('has_budget_silos', [1 => 'Yes', 0 => 'No'], $has_budget_silos, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please confirm if the campaign has specific budget splits that must be adhered to e.g. spend by channel / product ie - Rich Media. (Please note budget siloing is not recommended by OMG Programmatic as it may result in underspend)']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('budget_silos_main', 'Budget Silos', ['class' => 'control-label']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('silo_description', 'Silo Description:', ['class' => 'control-label']) !!}
                {!! Form::text('budget_silos[0][silo_description]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[1][silo_description]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[2][silo_description]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[3][silo_description]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silo_total_label', 'Total', ['class' => 'form-control', 'readonly']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('silo_budget', 'Budget:', ['class' => 'control-label']) !!}
                {!! Form::text('budget_silos[0][silo_budget]', null, ['class' => 'form-control', 'id' => 'silo_budget_0']) !!}
                {!! Form::text('budget_silos[1][silo_budget]', null, ['class' => 'form-control', 'id' => 'silo_budget_1']) !!}
                {!! Form::text('budget_silos[2][silo_budget]', null, ['class' => 'form-control', 'id' => 'silo_budget_2']) !!}
                {!! Form::text('budget_silos[3][silo_budget]', null, ['class' => 'form-control', 'id' => 'silo_budget_3']) !!}
                {!! Form::text('budget_silos_total', null, ['class' => 'form-control', 'readonly', 'id' => 'budget_silos_total']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('planning_cpm', 'Planning CPM:', ['class' => 'control-label']) !!}
                {!! Form::text('budget_silos[0][planning_cpm]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[1][planning_cpm]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[2][planning_cpm]', null, ['class' => 'form-control']) !!}
                {!! Form::text('budget_silos[3][planning_cpm]', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6 @if ($errors->has('targeting_requirements')) has-error @endif">
                {!! Form::label('targeting_requirements', 'Targeting Requirements:', ['class' => 'control-label']) !!}
                {!! Form::textarea('targeting_requirements', null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please note any specific limitations or requirements that must be adhered to e.g. audience profile, category or contextual targets, frequency caps, retargeting only, day parting etc. Please note these should only be absolute prescriptions and not suggestions.']) !!}

                Please note any specific limitations or requirements that must be adhered to e.g. audience profile, category or contextual targets, frequency caps, retargeting only, day parting etc. Please note these should only be absolute prescriptions and not suggestions.
            </div>
        </div>

        <legend>Asset Delivery</legend>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('requested_tracking_pixels', 'Have you requested necessary onsite tracking pixels from OMG Programmatic?', ['class' => 'required control-label', 'data-toggle' => 'tooltip', 'title' => 'Request pixels at least 2 weeks prior to campaign activity.']) !!}<br>
                {!! Form::select('requested_tracking_pixels', ['Yes', 'No'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6 @if ($errors->has('tracking_pixel_details')) has-error @endif">
                {!! Form::label('tracking_pixel_details', 'If yes, please give names/IDs of the pixels', ['class' => 'required control-label']) !!}
                {!! Form::textarea('tracking_pixel_details', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6 @if ($errors->has('tracking_pixel_events')) has-error @endif">
                {!! Form::label('tracking_pixel_events', 'What are the events?', ['class' => 'required control-label']) !!}<br>
                {!! Form::textarea('tracking_pixel_events', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('tracking_tag', 'Tracking Tag', ['class' => 'control-label' , 'data-toggle' => 'tooltip', 'title' => 'Please confirm DSP pixel to be used for kpi event tracking and metric to monitor For DCM - DBM campaigns include specific floodlight ids']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('dsp_pixel_name', 'DSP Pixel Name:', ['class' => 'control-label']) !!}
                {!! Form::text('tracking_tag[0][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[1][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[2][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[3][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('metric_tracking', 'Metric Tracking:', ['class' => 'control-label']) !!}
                {!! Form::text('tracking_tag[0][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[1][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[2][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag[3][metric_tracking]', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('is_rich_media', 'Are you running Rich media?', ['class' => 'required control-label']) !!}<br>
                {!! Form::select('is_rich_media', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('rm_creative_formats', 'What rich media creative formats will be supplied?', array('class' => 'required control-label')) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2">
                {!! Form::label('rm_creative_formats', 'Scoota', array('class' => 'control-label')) !!}
            </div>

            <div class="form-group col-md-2">
                {!! Form::label('rm_creative_formats', 'Sublime Skinz (with Adylic Build)', array('class' => 'control-label')) !!}
            </div>

            <div class="form-group col-md-2">
                {!! Form::label('rm_creative_formats', 'Sublime Skinz', array('class' => 'control-label')) !!}
            </div>

            <div class="form-group col-md-2">
                {!! Form::label('rm_creative_formats', 'Collective', array('class' => 'control-label')) !!}
            </div>

            <div class="form-group col-md-2">
                {!! Form::label('rm_creative_formats', 'Other', array('class' => 'control-label')) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2">

                @if (empty($booking_detail->rm_creative_format) == false)
                    @php $selected_creative_formats = json_decode($booking_detail->rm_creative_format) @endphp
                @else
                    @php $selected_creative_formats = array() @endphp
                @endif


                @foreach ($rich_media_creative_formats_1 as $rich_media_creative_format)
                    @php $selected = false; @endphp
                    @if (in_array($rich_media_creative_format, $selected_creative_formats))
                        @php $selected = true; @endphp
                    @endif

                    <label class="checkbox-inline">
                        {!! Form::checkbox('rm_creative_format[]', $rich_media_creative_format, $selected, array('class' => 'rm_cf_checkbox')) !!} {{ $rich_media_creative_format }}
                    </label>
                @endforeach
            </div>

            <div class="form-group col-md-2">
                @foreach ($rich_media_creative_formats_2 as $rich_media_creative_format)
                    @php $selected = false; @endphp
                    @if (in_array($rich_media_creative_format, $selected_creative_formats))
                        @php $selected = true; @endphp
                    @endif

                    <label class="checkbox-inline">
                        {!! Form::checkbox('rm_creative_format[]', $rich_media_creative_format, $selected, array('class' => 'rm_cf_checkbox')) !!} {{ $rich_media_creative_format }}
                    </label>
                @endforeach
            </div>

            <div class="form-group col-md-2">
                @foreach ($rich_media_creative_formats_3 as $rich_media_creative_format)
                    @php $selected = false; @endphp
                    @if (in_array($rich_media_creative_format, $selected_creative_formats))
                        @php $selected = true; @endphp
                    @endif

                    <label class="checkbox-inline">
                        {!! Form::checkbox('rm_creative_format[]', $rich_media_creative_format, $selected, array('class' => 'rm_cf_checkbox')) !!} {{ $rich_media_creative_format }}
                    </label>
                @endforeach
            </div>

            <div class="form-group col-md-2">
                @foreach ($rich_media_creative_formats_4 as $rich_media_creative_format)
                    @php $selected = false; @endphp
                    @if (in_array($rich_media_creative_format, $selected_creative_formats))
                        @php $selected = true; @endphp
                    @endif
                    <label class="checkbox-inline">
                        {!! Form::checkbox('rm_creative_format[]', $rich_media_creative_format, $selected, array('class' => 'rm_cf_checkbox')) !!} {{ $rich_media_creative_format }}
                    </label>
                @endforeach
            </div>

            <div class="form-group col-md-2">
                @foreach ($rich_media_creative_formats_5 as $rich_media_creative_format)
                    @php $selected = false; @endphp

                    @if (in_array($rich_media_creative_format, $selected_creative_formats))
                        @php $selected = true; @endphp
                    @endif

                    <label class="checkbox-inline">
                        {!! Form::checkbox('rm_creative_format[]', $rich_media_creative_format, $selected, array('class' => 'rm_cf_checkbox')) !!} {{ $rich_media_creative_format }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6" id='rm_creative_format_div' {{ $rm_creative_other_visibility }}>
                {!! Form::label('rm_creative_format_other', 'Other', ['class' => 'control-label']) !!}
                {!! Form::textarea('rm_creative_format_other', $rm_creative_format_other_val, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('is_1x1_supplied', 'If yes, have you supplied 1x1 impression & click trackers to the 3rd Party?', ['class' => 'control-label']) !!}<br>
                {!! Form::select('is_1x1_supplied', [1 => 'Yes', 0 => 'No'], $is_1x1_supplied, ['class' => 'form-control']) !!}

            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('rm_creative_notes', 'Any additional notes for Rich Media creative', ['class' => 'control-label']) !!}
                {!! Form::textarea('rm_creative_notes', $rm_creative_notes, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('rm_creative_format', 'What creative formats will be supplied?', ['class' => 'control-label']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                @php
                    $format_types = array('Standard' => 'Standard', 'Mobile' => 'Mobile', 'Other' => 'Other');
                @endphp

                {!! Form::label('supplied_creative_formats', 'Format Type', ['class' => 'control-label']) !!}
                {!! Form::select('supplied_creative_formats[0][format_type]', $format_types, null, ['class' => 'form-control']) !!}
                {!! Form::select('supplied_creative_formats[1][format_type]', $format_types, null, ['class' => 'form-control']) !!}
                {!! Form::select('supplied_creative_formats[2][format_type]', $format_types, null, ['class' => 'form-control']) !!}
                {!! Form::select('supplied_creative_formats[3][format_type]', $format_types, null, ['class' => 'form-control']) !!}
                {!! Form::select('supplied_creative_formats[4][format_type]', $format_types, null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                @if ($errors->has('supplied_creative_formats.0.dimension'))
                    {!! Form::label('dimensions', 'Dimensions:', ['class' => 'label-error control-label']) !!}
                    {!! Form::text('supplied_creative_formats[0][dimension]', null, ['class' => 'input-error form-control']) !!}
                @else
                    {!! Form::label('dimensions', 'Dimensions:', ['class' => 'control-label']) !!}
                    {!! Form::text('supplied_creative_formats[0][dimension]', null, ['class' => 'form-control']) !!}
                @endif

                {!! Form::text('supplied_creative_formats[1][dimension]', null, ['class' => 'form-control']) !!}
                {!! Form::text('supplied_creative_formats[2][dimension]', null, ['class' => 'form-control']) !!}
                {!! Form::text('supplied_creative_formats[3][dimension]', null, ['class' => 'form-control']) !!}
                {!! Form::text('supplied_creative_formats[4][dimension]', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('specific_activity_tags', 'Please select Yes and indicate if tags should be targeted to specific activity rather than all tags in rotation', ['class' => 'control-label']) !!}<br>
                {!! Form::select('specific_activity_tags', [1 => 'Yes', 0 => 'No'], $specific_activity_tags, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-2 col-sm-3 col-xs-6 @if ($errors->has('data_collection_code')) has-error @endif">
                {!! Form::label('data_collection_code', 'Conversion ID (Adserver)', ['class' => 'required control-label']) !!}
                {!! Form::text('data_collection_code', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <legend>Reporting</legend>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('is_reporting', 'Reporting', ['class' => 'control-label']) !!}<br>
                {!! Form::select('is_reporting', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Weekly updates - specific requirements to be discussed with the OMG Programmatic team.' ]) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('weekly_updates', 'Reporting (Weekly updates - specific requirements to be discussed with the OMG Programmatic team.)', ['class' => 'control-label']) !!}
                {!! Form::textarea('weekly_updates', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('metrics_required', 'Metrics Required', ['class' => 'control-label']) !!}
                {!! Form::textarea('metrics_required', null, ['class' => ' form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('adserver_select', 'Adserver (please specify which Adserver is being used)', ['class' => 'required control-label']) !!}
                {!! Form::select('adserver_select', $adserver, $adserver_select_val, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please specify which Adserver is being used' ]) !!}
                {!! Form::hidden('adserver', null, array('id' => 'adserver')) !!}

            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6" id='adserver_div' {{ $adserver_other_visibility }}>
                {!! Form::label('adserver_input', 'Other', ['class' => 'control-label']) !!}
                {!! Form::text('adserver_input', $adserver_other_val, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6 @if ($errors->has('adserver_metric')) has-error @endif">
                {!! Form::label('adserver_metric', 'Please confirm the metric/conversion event to be used on the Adserver', ['class' => 'required control-label']) !!}
                {!! Form::text('adserver_metric', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('site_list', 'Site list', ['class' => 'control-label']) !!}
                {!! Form::textarea('site_list', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('audience_segment_examples', 'Audience segment examples', ['class' => 'control-label']) !!}
                {!! Form::textarea('audience_segment_examples', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('other_info', 'Other information', ['class' => 'control-label']) !!}
                {!! Form::textarea('other_info', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('omg_programmatic_assessment', 'OMG Programmatic Assessment (to be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected)', ['class' => 'control-label']) !!}
                {!! Form::textarea('omg_programmatic_assessment', null, ['class' => 'form-control' , 'data-toggle' => 'tooltip', 'title' => 'To be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected']) !!}
            </div>
        </div>
    <div class="box-footer">
        @if(\Baselib::canCreateBooking())
            {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
            <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
        @endif
    </div>
    {!! Form::close() !!}
</div>
</div>