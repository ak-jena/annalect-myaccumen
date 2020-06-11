@if($booking_detail !== null)
    @if($booking_detail->requested_tracking_pixels == NULL)
        @php
            $requested_tracking_pixels = 0;
        @endphp
    @else
        @php
            $requested_tracking_pixels = $booking_detail->requested_tracking_pixels;
        @endphp
    @endif
@else
    @php
        $requested_tracking_pixels = 0;
    @endphp
@endif

@section('subtitle','VOD')

<div class="box box-info">
    {!! Form::model(
        $booking_detail,
        [
            'route' => ['process-booking', $campaign->id, $product->id]
        ])
    !!}

    <div class="box-header">
        @include('partials.alerts.errors')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('requested_tracking_pixels', 'Have you requested necessary onsite tracking pixels from OMG Programmatic?', ['class' => 'control-label']) !!}<br>
                {!! Form::select('requested_tracking_pixels', [1 => 'Yes', 0 => 'No'], $requested_tracking_pixels, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('implemented_pixels', 'Have the OMG Programmatic pixels been implemented?', ['class' => 'control-label']) !!}<br>
                {!! Form::select('implemented_pixels', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6 @if ($errors->has('data_collection_code')) has-error @endif">
                {!! Form::label('data_collection_code', 'Data collection code', ['class' => 'control-label']) !!}
                {!! Form::text('data_collection_code', null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please declare any additional data collection pixels you will be including in the creative tag. We need to declare these to the AdExchanges and failure to do so can result in creative rejection from the exchange']) !!}
            </div>

        </div>

        <div class="row">
            <div class="form-group col-md-8">
                {!! Form::label('tracking_tag_dsp_main', 'Tracking tag (please confirm DSP pixel to be used for kpi event tracking and metric to monitor)', ['class' => 'control-label', 'data-toggle' => 'tooltip', 'title' => 'Please confirm DSP pixel to be used for kpi event tracking and metric to monitor']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('tracking_tag_dsp', 'DSP Pixel Name', ['class' => 'control-label']) !!}
                {!! Form::text('tracking_tag_dsp[0][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[1][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[2][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[3][dsp_pixel_name]', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('metric_tracking', 'Dimensions:', ['class' => 'control-label']) !!}
                {!! Form::text('tracking_tag_dsp[0][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[1][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[2][metric_tracking]', null, ['class' => 'form-control']) !!}
                {!! Form::text('tracking_tag_dsp[3][metric_tracking]', null, ['class' => 'form-control']) !!}
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
                {!! Form::textarea('omg_programmatic_assessment', null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'To be completed by OMG Programmatic for any campaign where specifics of the campaign or activity to be used by not deliver or perform as expected']) !!}
            </div>
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