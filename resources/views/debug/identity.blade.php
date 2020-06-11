@extends('app')
@section('title','Minerva')
@section('subtitle','App Debug and Maintainance')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">User Identity</h3>
            </div>
            <div class="box-body">
                <pre>
                    <?php print_r($user); ?>
                </pre>
            </div>
        </div>
    </div>
</div>        

@endsection('content')