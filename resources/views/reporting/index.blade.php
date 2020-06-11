@extends('app')
@section('title','Accuen Booking')
@section('subtitle','Export Report')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            {!! Form::open([
                'route' => 'update-reporting'
            ]) !!}
            <div class="box-header">
                @include('partials.alerts.errors')
                @if($existing_report_schedule !== null)
                    <legend>Existing Schedule</legend>
                    <b>Frequency: </b> {{ $existing_report_schedule->frequency }}<br>
                    <b>Recipients: </b> {{ $existing_report_schedule->recipients }}<br>
                @endif
            </div>
            <div class="box-body">
                <legend>New Schedule</legend>
                <p>All campaigns that are on the booking engine to date.</p>
                <div class="row">
                    <div class="form-group col-md-2 col-sm-3 col-xs-6">
                        {!! Form::label('frequency', 'Frequency', ['class' => 'control-label required']) !!}
                        {!! Form::select('frequency', ['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'], null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
                    </div>
                    <div class="form-group col-md-3 col-sm-3 col-xs-6">
                        {!! Form::label('recipients', 'Receipients Email Address (separated by a semicolon ";") ', ['class' => 'control-label required']) !!}
                        {!! Form::textarea('recipients', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>

        </div>
        <div class="box-footer margin-bottom-standard">
            {!! Form::submit('Update Schedule', ['class' => 'btn btn-primary']) !!}
            <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>

</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            {!! Form::open([
                'route' => 'export-report'
            ]) !!}
            <div class="box-body">
                <legend>One off Export</legend>
                <p>Date Range</p>
                    <ul>
                        <li>All campaigns that are upcoming on the booking engine (ie – a start date beyond the date you have picked)</li>
                        <li>Any campaigns that fall outside this start date, even if they are running in that month will not be counted.</li>
                    </ul>

                <div class="row">
                    <div class="form-group col-md-3 col-sm-3 col-xs-6">
                        {!! Form::label('recipients', 'Receipients Email Addresses (separated by a semicolon ";")', ['class' => 'control-label required']) !!}
                        {!! Form::textarea('recipients', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-2 col-sm-3 col-xs-6">
                        {!! Form::label('campaign_dates', 'Dates', array('class' => 'control-label required')) !!}
                        {!! Form::text('campaign_dates', null, array('class' => 'form-control', 'id' => 'campaign_dates')) !!}
                    </div>
                </div>
            </div>

        </div>
        <div class="box-footer">
            {!! Form::submit('Export', ['class' => 'btn btn-primary']) !!}
            <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('input#campaign_dates').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            "startDate":"24/04/2017"
        });
    });
</script>

@endsection('content')