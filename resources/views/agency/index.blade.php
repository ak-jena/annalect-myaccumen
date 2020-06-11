@extends('app')
@section('title','Minerva')
@section('subtitle','Agencies')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header">
                <a href="{{ route('agency.create') }}" class="btn btn-primary btn-flat">Create a new agency</a>
            </div>
            <div class="box-body">
                <table class="table table-hover table-striped table-condensed table-bordered" id="agencies-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Person</th>
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
    var oTable = $('#agencies-table').DataTable({
        responsive: true,
        autoWidth: false,
        //dom: 'ifrtpB', //Info + Filter + pRocessing + Table + Paging + Length + Buttons
        dom: '<"tbl-top pull-left"i>,<"tbl-top clearfix"fr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"B><"tbl-pagin pull-right"p>>',
        buttons: [
            { extend: 'copy', className: 'btn-sm', text: '<i class="fa fa-copy"></i> Copy', title: 'List of agencies', exportOptions: {columns: [2,3,4]} },
            { extend: 'print', className: 'btn-sm', text: '<i class="fa fa-print"></i> Print', title: 'List of agencies', exportOptions: {columns: [2,3,4]} },
            { extend: 'csv', className: 'btn-sm', text: '<i class="fa fa-file-text-o"></i> CSV', title: 'List of agencies', exportOptions: {columns: [2,3,4]} },
            { extend: 'excel', className: 'btn-sm', text: '<i class="fa fa-file-excel-o"></i> Excel', title: 'List of agencies', exportOptions: {columns: [2,3,4]} },
        ],
        processing: true,
        serverSide: false,
        iDisplayLength: {{Auth::user()->pagination}},
        language: {
            "processing": "<img src='{{ asset('img/ajax-loaders/ajax-loader-9.gif') }}'>" 
        },         
        ajax: {
            'url': '{{ URL::to('/agency/getagencies') }}',
            'type': 'POST',
             'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        },        
        columns: [
            { data: 'name', name: 'name', className: "text-nowrap" },
            { data: 'contact_person', name: 'contact_person' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
            
        ],
        order: [[ 1, 'asc' ]],
    });
    $('#agencies-table').on('draw.dt', function () {
        $("[rel=popover]").popover();
        $("[rel=tooltip]").tooltip(); 
    });
    $('#agencies-table').DataTable().on('click', '.btn-delete[data-remote]', function (e) {
        var url = $(this).data('remote');
        swal({   
            title: "Are you sure?",
            text: "This agency will be permanently deleted",
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
                    oTable.ajax.url('{{ URL::to('/agency/getagencies') }}').load();
                });
                swal("Deleted!", "Agency has been deleted.", "success");
        });   
    });    
});
</script>

@endsection('content')