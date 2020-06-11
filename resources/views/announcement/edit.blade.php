@extends('app')
@section('title','Minerva')
@section('subtitle','New announcement')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('/css/fontawesome-iconpicker.min.css') }}"/>
<script type="text/javascript" src="{{ asset('/js/fontawesome-iconpicker.min.js') }}"></script>  
<script type="text/javascript">
$(document).ready(function() {
    var elem = document.querySelector('.js-switch');
    var init = new Switchery(elem, {size: 'small', color: '#00a65a', jackColor: '#ffffff' });    

    $('.datepicker').datepicker({
      autoclose: true
    });
    
    $('.icp-auto').iconpicker();
    
});    
</script>

<div class="box box-info">
    {!! Form::model($announcement, [
        'method' => 'PATCH',
        'route' => ['announcement.update', $announcement->id]
    ]) !!}       
    <div class="box-header">
        @include('partials.alerts.errors')
    </div>
    <div class="box-body">
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <?php 
                    $user_groups = [0 => 'Everyone'] + DB::table('roles')->orderBy('id','asc')->pluck('name', 'id')->toArray();
                ?>
                {!! Form::label('user_group', 'To:', ['class' => 'control-label']) !!}
                {!! Form::select('user_group', $user_groups, null, ['class' => 'form-control']) !!}                    
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('is_active', 'Is Active:', ['class' => 'control-label']) !!}<br>
                {!! Form::checkbox('is_active', '1', null, ['class' => 'js-switch form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 col-sm-6 col-xs-12">
                {!! Form::label('message', 'Message:', ['class' => 'control-label']) !!}
                {!! Form::text('message', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('icon', 'Icon:', ['class' => 'control-label']) !!}
                {!! Form::text('icon', null, ['class' => 'form-control icp icp-auto', 'data-input-search'=>'true']) !!}
            </div>
            <div class="form-group col-md-2">
                {!! Form::label('url', 'Relative URL:', ['class' => 'control-label']) !!}
                {!! Form::text('url', null, ['class' => 'form-control']) !!}
            </div>
        </div>        
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('start_date', 'From:', ['class' => 'control-label']) !!}
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>                
                    {!! Form::text('start_date', null, ['class' => 'form-control pull-right datepicker']) !!}
                </div>    
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('end_date', 'Until:', ['class' => 'control-label']) !!}
                <div class="input-group date">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>                
                    {!! Form::text('end_date', null, ['class' => 'form-control pull-right datepicker']) !!}
                </div>    
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