@extends('app')

@section('title','Minerva')
@section('subtitle','App Debug and Maintainance')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Framework Storage & Caching</h3>
            </div>
            <div class="box-body">
                <a class="btn bg-maroon-gradient btn-app" href="{{url('debug/clear-cache')}}">
                    <i class="fa fa-trash-o"></i> Clear Cache
                </a>
                <a class="btn bg-maroon-gradient btn-app disabled">
                    <i class="fa fa-trash-o"></i> Clear Views
                </a>
                <a class="btn bg-maroon-gradient btn-app disabled">
                    <i class="fa fa-trash-o"></i> Clear Session
                </a>
            </div>
        </div>
    </div>    
    <div class="col-md-3">
        <div class="box box-success">    
            <div class="box-header">
              <h3 class="box-title">Global Variables & Paths</h3>
            </div>    
            <div class="box-body">
                <a class="btn btn-app" href="{{url('debug/identity')}}">
                    <i class="fa fa-user"></i> Identity Vars
                </a>
                <a class="btn btn-app" href="{{url('debug/session')}}">
                    <i class="fa fa-code"></i> Session Vars
                </a>
                <a class="btn btn-app" href="{{url('debug/path')}}">
                    <i class="fa fa-map-signs"></i> All Paths
                </a>
            </div>     
        </div>
    </div>      
    <div class="col-md-3">
        <div class="box box-warning">    
            <div class="box-header">
              <h3 class="box-title">Error Blades</h3>
            </div>    
            <div class="box-body">
                <a class="btn btn-app" href="{{url('debug/error403')}}">
                    <i class="fa fa-ban"></i> Error 403
                </a>
                <a class="btn btn-app" href="{{url('debug/error500')}}">
                    <i class="fa fa-server"></i> Error 500
                </a>
                <a class="btn btn-app" href="{{url('debug/error503')}}">
                    <i class="fa fa-hourglass-half"></i> Error 503
                </a>
            </div>     
        </div>
    </div>      
    <div class="col-md-3">
        <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Temporary Actions</h3>
            </div>
            <div class="box-body">
                <a class="btn btn-app" href="{{url('debug/action1')}}">
                    <i class="fa fa-bicycle"></i> Action 1
                </a>
                <a class="btn btn-app" href="{{url('debug/action2')}}">
                    <i class="fa fa-car"></i> Action 2
                </a>
                <a class="btn btn-app" href="{{url('debug/action3')}}">
                    <i class="fa fa-plane"></i> Action 3
                </a>
            </div>
        </div>
    </div>    
</div>
   
@endsection('content')