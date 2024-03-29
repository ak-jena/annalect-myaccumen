@extends('app')
@section('title','Minerva')
@section('subtitle','Users')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header">
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-flat">Create a new user</a>
            </div>
            <div class="box-body">
                <table class="table table-hover table-striped table-condensed table-bordered" id="users-table">
                    <thead>
                        <tr>
                            <th style="width: 20px"></th>
                            <th style="width: 30px">Avatar</th>                            
                            <th>Name</th>
                            <th>Username</th>
                            <th>E-mail</th>
                            <th>Role</th>
                            <th>Last login</th>
                            <th style="width: 100px">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>        

<script type="text/javascript">
$(document).ready(function() {
    var oTable = $('#users-table').DataTable({
        responsive: true,
        autoWidth: false,
        //dom: 'ifrtpB', //Info + Filter + pRocessing + Table + Paging + Length + Buttons
        dom: '<"tbl-top pull-left"i>,<"tbl-top clearfix"fr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"B><"tbl-pagin pull-right"p>>',
        buttons: [
            { extend: 'copy', className: 'btn-sm', text: '<i class="fa fa-copy"></i> Copy', title: 'List of users', exportOptions: {columns: [2,3,4]} },
            { extend: 'print', className: 'btn-sm', text: '<i class="fa fa-print"></i> Print', title: 'List of users', exportOptions: {columns: [2,3,4]} },
            { extend: 'csv', className: 'btn-sm', text: '<i class="fa fa-file-text-o"></i> CSV', title: 'List of users', exportOptions: {columns: [2,3,4]} },                
            { extend: 'excel', className: 'btn-sm', text: '<i class="fa fa-file-excel-o"></i> Excel', title: 'List of users', exportOptions: {columns: [2,3,4]} },
        ],
        processing: true,
        serverSide: false,
        iDisplayLength: {{Auth::user()->pagination}},
        language: {
            "processing": "<img src='{{ asset('img/ajax-loaders/ajax-loader-9.gif') }}'>" 
        },         
        ajax: {
            'url': '{{ URL::to('/user/getusers') }}', 
            'type': 'POST',
             'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        },        
        columns: [
            { data: 'blocked', name: 'blocked', searchable: false, className: 'text-center' },    
            { data: 'avatar', name: 'avatar', orderable: false, searchable: false, className: 'text-center' },            
            { data: 'name', name: 'name', className: "text-nowrap" },
            { data: 'username', name: 'username' },
            { data: 'email', name: 'email' },
            { data: 'role_name', name: 'role_name' },
            { data: 'last_login', name: 'last_login' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            
        ],
        order: [[ 2, 'asc' ]],
    });
    $('#users-table').on('draw.dt', function () {
        $("[rel=popover]").popover();
        $("[rel=tooltip]").tooltip(); 
    });
    $('#users-table').DataTable().on('click', '.btn-delete[data-remote]', function (e) {
        var url = $(this).data('remote');
        swal({   
            title: "Are you sure?",
            text: "This user account will be permanently deleted",
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Delete", 
            closeOnConfirm: false 
        }, 
        function(){
                e.preventDefault();
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    //data: {method: '_DELETE'},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')}
                }).always(function (data) {
                    oTable.ajax.url('{{ URL::to('/user/getusers') }}').load();
                });
                swal("Deleted!", "User account has been deleted.", "success");
        });   
    });    
});
</script>

@endsection('content')