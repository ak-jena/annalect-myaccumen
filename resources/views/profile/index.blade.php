@extends('app')
@section('title','Minerva')
@section('subtitle','Profile')
@section('content')

<script type="text/javascript">
    $(function (){
        $("[rel=popover]").popover();  
    });
</script>

<div class="row">
  <div class="box box-widget widget-user">
    <div class="widget-user-header bg-teal-gradient">
      <h3 class="widget-user-username">{{Auth::user()->name}}</h3>
      <h5 class="widget-user-desc">Accuen {{Session::get('user_role')}}</h5>
    </div>
    <a href='https://en.gravatar.com/' target='_blank'>  
        <div class="widget-user-image" rel='popover' data-trigger='hover' data-container='body' data-placement='right' data-original-title='Your global avatar' data-content='To set your avatar, click me and sign-up with Gravatar using the same e-mail address as the one below'>
          <img class="img-circle" src="{{ Baselib::get_gravatar(Auth::user()->email) }}">
        </div>
    </a>    
    <div class="box-footer">
      <div class="row">
        <div class="col-sm-12">
          <div class="description-block">
              <i class="fa fa-lg fa-info-circle text-muted"></i> <span class="text-muted">To set your avatar, simply sign-up with <a href='https://en.gravatar.com/' target='_blank'>Gravatar</a> using your email address below</span>
          </div>
        </div>
      </div>
    </div>
    <div class="box-header">
        @include('partials.alerts.errors')
    </div>
    <div class="box-body">
        {!! Form::model($user, [
            'method' => 'POST',
            'route' => ['profile.update']
        ]) !!}                
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control','disabled'=>'true']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('username', 'Username:', ['class' => 'control-label']) !!}
                {!! Form::text('username', null, ['class' => 'form-control','disabled'=>'true']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
                {!! Form::text('email', null, ['class' => 'form-control','disabled'=>'true']) !!}
            </div>                    
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('newPassword', 'Password:', ['class' => 'control-label']) !!}
                {!! Form::text('newPassword', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        {!! Form::submit('Update password', ['class' => 'btn btn-primary']) !!}
        <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
        {!! Form::close() !!}                
    </div>

  </div>    
</div>

@endsection('content')