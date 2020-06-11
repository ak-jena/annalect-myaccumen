@extends('app')
@section('title','Minerva')
@section('subtitle','Clients')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header">
                <a href="{{ route('client.create') }}" class="btn btn-primary btn-flat">Create a new client</a>
            </div>
            <div class="box-body">
                <table class="table table-hover table-striped table-condensed table-bordered" id="clients-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Agency</th>
                            <th>Model</th>
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
    var oTable = $('#clients-table').DataTable({
        responsive: true,
        autoWidth: false,
        //dom: 'ifrtpB', //Info + Filter + pRocessing + Table + Paging + Length + Buttons
        dom: '<"tbl-top pull-left"i>,<"tbl-top clearfix"fr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"B><"tbl-pagin pull-right"p>>',
        buttons: [
            { extend: 'copy', className: 'btn-sm', text: '<i class="fa fa-copy"></i> Copy', title: 'List of clients', exportOptions: {columns: [2,3,4]} },
            { extend: 'print', className: 'btn-sm', text: '<i class="fa fa-print"></i> Print', title: 'List of clients', exportOptions: {columns: [2,3,4]} },
            { extend: 'csv', className: 'btn-sm', text: '<i class="fa fa-file-text-o"></i> CSV', title: 'List of clients', exportOptions: {columns: [2,3,4]} },
            { extend: 'excel', className: 'btn-sm', text: '<i class="fa fa-file-excel-o"></i> Excel', title: 'List of clients', exportOptions: {columns: [2,3,4]} },
        ],
        processing: true,
        serverSide: false,
        iDisplayLength: {{Auth::user()->pagination}},
        language: {
            "processing": "<img src='{{ asset('img/ajax-loaders/ajax-loader-9.gif') }}'>" 
        },         
        ajax: {
            'url': '{{ URL::to('/client/getclients') }}',
            'type': 'POST',
             'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        },        
        columns: [
            { data: 'name', name: 'name', className: "text-nowrap" },
            { data: 'agency_name', name: 'agency_name'},
            { data: 'model', name: 'model' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            
        ],
        order: [[ 1, 'asc' ]],
    });
    $('#clients-table').on('draw.dt', function () {
        $("[rel=popover]").popover();
        $("[rel=tooltip]").tooltip(); 
    });
    $('#clients-table').DataTable().on('click', '.btn-delete[data-remote]', function (e) {
        var url = $(this).data('remote');
        swal({   
            title: "Are you sure?",
            text: "This client will be permanently deleted",
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
                    oTable.ajax.url('{{ URL::to('/client/getclients') }}').load();
                });
                swal("Deleted!", "Client has been deleted.", "success");
        });   
    });    
});
</script>

@endsection('content')