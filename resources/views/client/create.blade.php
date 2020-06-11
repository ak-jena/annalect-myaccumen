@extends('app')
@section('title','Accuen')
@section('subtitle','Create a new client')
@section('content')


<div class="box box-info">
    {!! Form::open([
        'route' => 'client.store',
        'enctype' => 'multipart/form-data'
    ]) !!}    
    <div class="box-header">
        @include('partials.alerts.errors')
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
                {!! Form::label('agency', 'Agency:', ['class' => 'control-label']) !!}
                {!! Form::select('agency', $agencies, null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
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
        {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
        <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
    </div>
    {!! Form::close() !!}    
</div>
     

@endsection('content')