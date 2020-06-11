<?php
    $a = array(
       'password' => '${P:8}',
    );

	$m = array(
            'Web Password' => 'password',
        );
	
	$myPassGen = new \App\PassGen($a, $m);

        try {
            $r = $myPassGen->gen();
	} catch (PassGenBadPattern $e) {
            echo "Bad generator pattern: '",  $e->getMessage(), "'\n";
        }

?>
@extends('app')
@section('title','Minerva')
@section('subtitle','Create a new user')
@section('content')

<script type="text/javascript">

// create js object of clients by agency id
var clients_by_agency = {
    @foreach($all_agencies as $agency)
        "{{ $agency->id }}" :
        [
            @foreach($agency->clients as $client)
            {
                'id': '{{ $client->id }}',
                'name': '{{ $client->name }}'
            },
            @endforeach
        ],
    @endforeach
}

//console.log(clients_by_agency);

function toggleMultipleAgencySelector(agencyId){
    if(agencyId == 10){
        // show multiple agency select
        $("#multiple-agencies").show("slow");
        $("#clients").hide("slow");
    }else{
        $("#multiple-agencies").hide("slow");
    }
}

function toggleClientsSelector(roleId, agencyId){
    if(roleId == 2 && agencyId != 10){
        // show client select
        $("#clients").show("slow");
        populateClientsSelect(agencyId);
    }else{
        // empty clients select
        $("#clients\\[\\]").find('option')
                .remove()
                .end();
        $("#clients").hide("slow");

    }
}

function populateClientsSelect(agencyId){
//    console.log('populateClientsSelect('+agencyId+') fired');
    // empty clients select
    $("#clients\\[\\]").find('option')
        .remove()

    $.each(clients_by_agency[agencyId], function(index, value){
        $('#clients\\[\\]').append($('<option />').val(value.id).text(value.name));
//        console.log(value.id+': '+value.name);
    });

}

// create js object of clients by agency

$(document).ready(function() {

    toggleMultipleAgencySelector($('#agency_id').val());
    toggleClientsSelector($("#role_id").val(), $('#agency_id').val());

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
      var switchery = new Switchery(html);
    });
    
    var elem = document.querySelector('.js-switch-red');
    var init = new Switchery(elem, {color: '#ff0000', jackColor: '#ffffff' });
    
    $("#name").change(function() {                
        $("#username").val(this.value.split(" ").join(".").toLowerCase());
        $("#email").val(this.value.split(" ").join(".").toLowerCase() + '@accuenmedia.com');
    });

    $("#agency_id").change(function() {
        var roleId = $("#role_id").val();

        toggleMultipleAgencySelector($(this).val());
        toggleClientsSelector(roleId, $(this).val());
    });

    $("#role_id").change(function() {
        var agencyId = $("#agency_id").val();
        toggleClientsSelector($(this).val(), agencyId);
    });
});    
</script>


<div class="box box-info">
    {!! Form::open([
        'route' => 'user.store'
    ]) !!}    
    <div class="box-header">
        @include('partials.alerts.errors')
    </div>
    <div class="box-body">
        <legend>Account</legend>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('role_id', 'Account Type:', ['class' => 'control-label']) !!}
                {!! Form::select('role_id', $acc_types, '1', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('blocked', 'Block access:', ['class' => 'control-label']) !!}<br>
                {!! Form::checkbox('blocked', '1', null, ['class' => 'js-switch-red form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('name', 'Name:', ['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('email', 'Email:', ['class' => 'control-label']) !!}
                {!! Form::text('email', null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('username', 'Username:', ['class' => 'control-label']) !!}
                {!! Form::text('username', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('password', 'Password:', ['class' => 'control-label']) !!}
                {!! Form::text('password', $r['password'], ['class' => 'form-control text-green text-bold']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                <?php
                // to prevent accuen from being selected twice
                $multipleAgencies = DB::table('agencies')->where('name', '!=', 'Accuen')->orderBy('id','asc')->pluck('name', 'id');
                ?>
                {!! Form::label('agency_id', 'Agency:', ['class' => 'control-label']) !!}
                {!! Form::select('agency_id', $agency_options, null, ['class' => 'form-control', 'placeholder' => 'Please select']) !!}
            </div>
            <div id="multiple-agencies" class="form-group col-md-2 col-sm-3 col-xs-6" style="display: none;">
                {!! Form::label('agencies[]', 'Additional Agencies:', ['class' => 'control-label']) !!}
                {!! Form::select('agencies[]', $multipleAgencies, null, ['class' => 'form-control', 'placeholder' => 'Please select', 'multiple' => true]) !!}
            </div>
        </div>

        <div class="row">
            <div id="clients" class="form-group col-md-2 col-sm-3 col-xs-6" style="display: none;">
                {!! Form::label('clients[]', 'Clients:', ['class' => 'control-label']) !!}
                {!! Form::select('clients[]', array(), null, ['class' => 'form-control', 'multiple' => true]) !!}
            </div>
        </div>
        
        <legend>Privilege</legend>
        <div class="row">
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('can_viewas', 'Can Use View-as:', ['class' => 'control-label']) !!}<br>
                {!! Form::checkbox('can_viewas', '1', true, ['class' => 'js-switch form-control']) !!}
            </div>
            <div class="form-group col-md-2 col-sm-3 col-xs-6">
                {!! Form::label('can_manage_user', 'Can Manage Users', ['class' => 'control-label']) !!}<br>
                {!! Form::checkbox('can_manage_user', '1', null, ['class' => 'js-switch form-control']) !!}
            </div>
        </div>        
    </div>
    <div class="box-footer">
        {!! Form::submit('Create', ['class' => 'btn btn-primary']) !!}
        <a href="javascript:history.back()" class="btn btn-default">Cancel</a>
    </div>
    {!! Form::close() !!}    
</div>
     

@endsection('content')