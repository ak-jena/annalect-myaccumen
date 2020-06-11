@extends('app')
@section('title','Minerva')
@section('subtitle','Updating Client: ' .$client->name)
@section('content')

<script type="text/javascript">
$(document).ready(function() {
    
    $("#name").change(function() {                
        $("#username").val(this.value.split(" ").join(".").toLowerCase());
        $("#email").val(this.value.split(" ").join(".").toLowerCase() + '@accuenmedia.com');
    });
});    
</script>

<div class="box box-info">
    {!! Form::model($client, [
        'method' => 'PATCH',
        'route' => ['client.update', $client->id],
        'enctype' => 'multipart/form-data'
    ]) !!}      
    <div class="box-header">
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                @if($client->logo == null)
                    <br><br><span class="text-muted">No logo uploaded.</span>
                @else
                    <img src="{{ Storage::disk('public')->url($client->logo) }}"  style="height:100px;" class="img-circle img-bordered"/>
                @endif
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <br><br><span class="text-muted"><i class="fa fa-sign-in"></i> Last updated:<br>{{$client->updated_at}}</span>
            </div>
        </div>
        @include('partials.alerts.errors')
    </div>
    <div class="box-header">
    </div>    
    <div class="box-body">
        <legend>Client</legend>
        
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <?php
                $agencies = DB::table('agencies')->orderBy('id','asc')->pluck('name', 'id');
                ?>
                {!! Form::label('agency_id', 'Agency:', ['class' => 'control-label']) !!}
                {!! Form::select('agency_id', $agencies, null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('model', 'Model:', ['class' => 'control-label']) !!}
                {!! Form::select('model', array('bundled' => 'Bundled', 'unbundled' => 'Unbundled'), null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('logo', 'Logo:', ['class' => 'control-label']) !!}
                {!! Form::file('logo', null, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    <div class="box-footer">
        {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
        <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
    </div>
    {!! Form::close() !!}    
</div>
@endsection('content')