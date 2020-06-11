@extends('app')
@section('title','Accuen')
@section('subtitle','Reject Booking Form')
@section('content')

    <script type="text/javascript">
        function toggleOtherReason(reason){
            if(reason == 'Other'){
                // show other textarea
                $("#other-reason-div").show("slow");
                $('#other-reason-div textarea').prop('disabled', false);
            }else{
                $("#other-reason-div").hide("slow");
                $('#other-reason-div textarea').prop('disabled', true);
            }
        }

        $(document).ready(function() {

            toggleOtherReason($('#rejection_reason').val());

            $("#rejection_reason").change(function() {
                toggleOtherReason($(this).val());
            });
        });
    </script>

    <div class="box box-info">
        {!! Form::open([
            'route' => 'process-bf-rejection',
        ]) !!}

        <input type="hidden" name="brief_id" value="{{ $campaign->brief->id }}">
        <input type="hidden" name="op_type" value="{{ $op_type }}">

        <div class="box-header">
            @include('partials.alerts.errors')
        </div>
        <div class="box-body">
            <legend>Campaign: {{ $campaign->brief->campaign_name }}</legend>
            <div class="row">
                <div class="form-group col-md-2 col-sm-3 col-xs-6">
                    {!! Form::label('rejection_reason', 'Cancellation Reason:', ['class' => 'control-label']) !!}
                    {!! Form::select('rejection_reason', $rejection_reasons, null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
                </div>
                <div id="other-reason-div" class="form-group col-md-2 col-sm-3 col-xs-6">
                    {!! Form::label('other_reason', 'Other:', ['class' => 'control-label']) !!}
                    {!! Form::textarea('other_reason', null, array('class' => 'form-control', 'cols'=> '50', 'rows' => '5')) !!}
                </div>
            </div>

        </div>
        <div class="box-footer">
            {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
            <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>


@endsection('content')