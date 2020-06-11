@if($booking_detail !== null)
    @if($booking_detail->pricing_model == NULL)
        @php
            $pricing_model = 'Variable CPM';
        @endphp
    @else
        @php
            $pricing_model = $booking_detail->pricing_model;
        @endphp
    @endif

    @if($booking_detail->has_budget_silos == NULL)
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
@else
    @php
        $pricing_model = 'Variable CPM';
        $has_budget_silos = 0;
        $specific_activity_tags = 0;
    @endphp
@endif

@section('subtitle','Audio')

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
        <legend>Campaign Booking Information</legend>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('pricing_model', 'Pricing model:', ['class' => 'control-label']) !!}
                {!! Form::text('pricing_model', $pricing_model, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('has_budget_silos', 'Is Budget Silos?', ['class' => 'required control-label']) !!}<br>
                {!! Form::select('has_budget_silos', [1 => 'Yes', 0 => 'No'], $has_budget_silos, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-8">
                {!! Form::label('budget_silos_main', 'Budget Silos (please confirm if the campaign has specific budget splits that must be adhered to e.g. spend by channel. Please note budget siloing is not recommended by OMG Programmatic as it may result in underspend)', ['class' => 'control-label']) !!}
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
        </div>

        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6 @if ($errors->has('targeting_requirements')) has-error @endif">
                {!! Form::label('targeting_requirements', 'Targeting Requirements:', ['class' => 'required control-label']) !!}
                {!! Form::textarea('targeting_requirements', null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please note any specific limitations or requirements that must be adhered to e.g. audience profile, category or contextual targets, frequency caps, retargeting only, day parting etc. Please note these should only be absolute prescriptions and not suggestions']) !!}

            </div>
        </div>

        <legend>Asset Delivery</legend>

        <div class="row">
            <div class="form-group col-md-8">
                {!! Form::label('creative_formats', 'What creative formats will be supplied?', ['class' => 'required control-label', 'data-toggle' => 'tooltip', 'title' => 'Please note: for maximum reach and scale we will require a 30" audio file']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                @php
                    $format_types = array('15s', '20s', '30s', '40s', '60s', 'Companion banner');
                @endphp

                {!! Form::label('supplied_creative_formats', 'Format type:', ['class' => 'control-label']) !!}
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

            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('1x1_adserver_trackers', '1x1 Ad Server Trackers', ['class' => 'control-label']) !!}<br>
                {!! Form::text('1x1_adserver_trackers', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6 @if ($errors->has('data_collection_code')) has-error @endif">
                {!! Form::label('data_collection_code', 'Conversion ID (Adserver)', ['class' => 'control-label required']) !!}
                {!! Form::text('data_collection_code', null, ['class' => 'form-control', 'data-toggle' => 'tooltip', 'title' => 'Please declare any additional data collection pixels you will be including in the creative tag. We need to declare these to the AdExchanges and failure to do so can result in creative rejection from the exchange']) !!}
            </div>

        </div>

        <legend>Reporting</legend>
        <div class="row">
            <div class="form-group col-md-6 col-sm-3 col-xs-6">
                {!! Form::label('reporting_description', 'Reporting:', ['class' => 'control-label']) !!}
                {!! Form::textarea('reporting_description', null, ['class' => 'form-control']) !!}
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





