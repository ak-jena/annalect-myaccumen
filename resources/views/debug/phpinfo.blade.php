@extends('app')
@section('title','Minerva')
@section('subtitle','App Debug and Maintainance')
@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
        PHP INFO
    </div>
    <div class="panel-body">
        <?php phpinfo(); ?>
    </div>
</div>

@endsection('content')