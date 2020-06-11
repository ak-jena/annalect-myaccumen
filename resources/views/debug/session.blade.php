@extends('app')

@section('title','Minerva')
@section('subtitle','App Debug and Maintainance')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Session variables</h3>
            </div>
            <div class="box-body">
                <pre>
                    <?php
                        var_dump($data); 
                    ?>
                </pre>
            </div>
        </div>
    </div>    
</div>
</div>
   
@endsection('content')