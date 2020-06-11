@extends('app')
@section('title','Minerva')
@section('subtitle','App Debug and Maintainance')
@section('content')

<script type="text/javascript">
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
        $("[rel=popover]").popover();   
    });
</script>


<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-body">
        
            <span class="fa fa-user text-green" rel="popover" data-trigger="hover" data-container="body" data-placement="auto left" data-content="Body Text" data-original-title="Title Text"></span>
        
            <table class="table table-hover table-striped table-bordered" id="users-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $status = "<a href='#' title='Account blocked' data-toggle='popover' data-trigger='hover' data-content='Some content'><i class='fa fa-ban text-red'></i></a>";
                    ?>
                    <tr>
                        <td style="width: 20px">{!! $status !!}</td>
                        <td>Tuan</td>
                        <td>tuan.nguyen</td>                        
                        <td>tuan.nguyen@accuenmedia.com</td>
                    </tr>
     
                </tbody>
            </table>
    </div>
</div>

@endsection('content')