@extends('app')
@section('title','Minerva')
@section('subtitle','Updating Agency: ' .$agency->name)
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
    {!! Form::model($agency, [
        'method' => 'PATCH',
        'route' => ['agency.update', $agency->id],
        'enctype' => 'multipart/form-data'
    ]) !!}      
    <div class="box-header">
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                @if($agency->logo == null)
                    <br><br><span class="text-muted">No logo uploaded.</span>
                @else
                    <img src="{{ Storage::disk('public')->url($agency->logo) }}"  style="height:100px;" class="img-circle img-bordered"/>
                @endif
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <br><br><span class="text-muted"><i class="fa fa-sign-in"></i> Last updated:<br>{{$agency->updated_at}}</span>
            </div>
        </div>
        @include('partials.alerts.errors')
    </div>
    <div class="box-header">
    </div>    
    <div class="box-body">
        <legend>Agency</legend>
        
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <?php 
                    $users = DB::table('users')->orderBy('id','asc')->pluck('name', 'id');
                ?>
                {!! Form::label('contact_user_id', 'Contact Person:', ['class' => 'control-label']) !!}
                {!! Form::select('contact_user_id', $users, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
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