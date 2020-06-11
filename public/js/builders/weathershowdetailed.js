$(document).ready(function(){

	// Trigger modal to delete a single campaign from the list
	$(".delete-campaign-icon").click(function(){
		var campaign_id = $(this).data('campaignid');
		var id 			= $(this).data('id');
		$("#delete-campaign-confirmation-li").html(campaign_id);
		$("input[name='campaign-delete-id']").val(id);
		$("#modal-delete-campaign").modal();
	});

	// Trigger modal to delete the whole list
	$("#delete-list-button-trigger").click(function(){
		$("#modal-delete-list").modal();
	});


	// Edit the current query
	$(".query-info").dblclick(function(){
		var target = $(this).attr('target');
		$(this).hide();
		$(".query-edit[target='"+target+"']").show();
		$(this).parent().removeClass('editable');
		console.log(target);
	});
	$(".query-update-cancel").click(function(){
		var target = $(this).parent().attr('target');
		$(".query-edit[target='"+target+"']").hide();
		$(".query-info[target='"+target+"']").show();
		$(".query-info[target='"+target+"']").parent().addClass('editable');
	});
	$(".query-update-ok").click(function(){
		var target = $(this).parent().attr('target');
		ajaxUpdateQuery(target);
	});
	$("input[name='end-date']").datepicker({
        autoclose: true
    });
});

function ajaxUpdateQuery(target){
	var listid 	= $("input[name='listid']").val();
	var action 	= $(".query-edit[target='"+target+"']").children('.edit-field-action').val();
	var token 	= $("input[name='_token']").val();
	if(target == 'precipitation'){
		var value 	= $(".query-edit[target='"+target+"']").children('.edit-field-value').val();
	}else if(target == 'temperature' || target == 'cloud'){
		var value 	= $(".query-edit[target='"+target+"']").children('div').children('.edit-field-value').val();
	}else if(target == 'enddate'){
		var value 	= $(".query-edit[target='"+target+"']").children('input').val();
	}

	
	if(action != '' && value == ''){
		showToast("Error","You left the fields in blank. Introduce at least one option.",-1); 
	}
	else{
		$.ajax({
	   		type : 		"POST",
	   		dataType : 	"json", 
			url : 		"/builders/weather/ajax-update-query",
			data : 		{ listid : listid, target : target, action : action, value : value, _token : token },
			beforeSend: function( xhr ) {
				$(".edit-buttons[target='"+target+"']").hide();
				$(".loading-buttons[target='"+target+"']").show();
		  	}
		})
		.done(function( data ) {
			//data = JSON.parse(data);
			if(data['error'] == 0){
				data = data['data'];
				html = " - ";
				if(data['action'] != ""){
					html = data['action']+" "+data['value'];
				}
				$(".query-info[target='"+data['target']+"']").html(html);

				$(".loading-buttons[target='"+target+"']").hide();
				$(".edit-buttons[target='"+target+"']").show();
				$(".query-edit[target='"+data['target']+"']").hide();
				$(".query-info[target='"+data['target']+"']").show();
				showToast("Success","Data updated correctly",3); 
			}else{
				$(".loading-buttons[target='"+target+"']").hide();
				$(".edit-buttons[target='"+target+"']").show();
				showToast("Error","There was an error processing your request. Try again later.",-1); 
			}
		}).error(function(data){
			$(".loading-buttons[target='"+target+"']").hide();
			$(".edit-buttons[target='"+target+"']").show();
			showToast("Error","There was an error processing your request. Try again later.",-1); 
		});
	}
}