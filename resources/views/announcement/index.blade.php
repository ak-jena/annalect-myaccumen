@extends('app')
@section('title','Minerva')
@section('subtitle','Announcements')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="box box-info">
            <div class="box-header">
                <a href="{{ route('announcement.create') }}" class="btn btn-primary btn-flat">New announcement</a>
            </div>
            <div class="box-body">
                <table class="table table-hover table-striped table-condensed table-bordered" id="announcements-table">
                    <thead>
                        <tr>
                            <th style="width: 60px">Status</th>
                            <th>To</th>
                            <th>Message</th>
                            <th>Displayed Since</th>
                            <th>Until</th>
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
    var oTable = $('#announcements-table').DataTable({
        responsive: true,
        autoWidth: false,
        //dom: 'ifrtpB', //Info + Filter + pRocessing + Table + Paging + Length + Buttons
        dom: '<"tbl-top pull-left"i>,<"tbl-top clearfix"fr>,t,<"tbl-footer clearfix"<"tbl-info pull-left"B><"tbl-pagin pull-right"p>>',
        buttons: [
            { extend: 'copy', className: 'btn-sm', text: '<i class="fa fa-copy"></i> Copy'},
            { extend: 'print', className: 'btn-sm', text: '<i class="fa fa-print"></i> Print'},
            { extend: 'csv', className: 'btn-sm', text: '<i class="fa fa-file-text-o"></i> CSV'},                
            { extend: 'excel', className: 'btn-sm', text: '<i class="fa fa-file-excel-o"></i> Excel'},
        ],
        processing: true,
        serverSide: false,
        iDisplayLength: {{Auth::user()->pagination}},
        language: {
            "processing": "<img src='{{ asset('img/ajax-loaders/ajax-loader-9.gif') }}'>" 
        },         
        ajax: {
            'url': '{{ URL::to('/announcement/getdata') }}', 
            'type': 'POST',
             'headers': {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        },        
        columns: [
            { data: 'is_active', name: 'is_active', searchable: false, className: 'text-center' },
            { data: 'user_group', name: 'user_group' },            
            { data: 'message', name: 'message' },
            { data: 'start_date', name: 'start_date' },
            { data: 'end_date', name: 'end_date' },
            { data: 'action', name: 'action', orderable: false, searchable: false },            
        ],
        order: [[ 3, 'desc' ]],
    });
    $('#announcements-table').on('draw.dt', function () {
        $("[rel=tooltip]").tooltip(); 
    });
    $('#announcements-table').DataTable().on('click', '.btn-delete[data-remote]', function (e) {
        var url = $(this).data('remote');
        swal({   
            title: "Are you sure?",
            text: "This announcement will be permanently deleted",
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
                    oTable.ajax.url('{{ URL::to('/announcement/getdata') }}').load();
                });
                swal("Deleted!", "Announcement has been deleted.", "success");
        });   
    });    
});
</script>

@endsection('content')