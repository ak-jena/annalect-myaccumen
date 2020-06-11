$(document).ready(function(){
	$("select[name='user']").select2({
		placeholder: "Select a user...",
		allowClear: true
	});
	$("select[name='agency']").select2({
		placeholder: "Select an agency...",
		allowClear: true
	});

	$("select[name='agency']").change(function(){
		$("form[name='form-agency-filter']").submit();
	});

	$("button[name='assign-all']").click(function(event){
		event.preventDefault();
		event.stopPropagation();

		var user_id 	= $("select[name='user']").val();
		var agency_id 	= $("select[name='agency']").val();
		var assign_type = $(this).val();

		$("input[name='assign-all-user-id']").val(user_id);
		$("input[name='assign-all-agency-id']").val(agency_id);
		$("input[name='assign-all-type']").val(assign_type);

		$("form[name='form-assign-all']").submit();
	});

	if($("input[name='success-msg']").length){
		var msg = $("input[name='success-msg']").val();
		showToast("Success",msg,3);
	}
});

function ajaxAssignUser(advertiser_id,dsp_id){
	var token 		= $("input[name='_token'").val();
	var user_id 	= $("select[name='user']").val()
	var user_name 	= $("select[name='user'] option:selected").text();
	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/admin/advertiser-user/ajax-user-assign",
		data : 		{user_id : user_id, advertiser_id : advertiser_id, dsp_id : dsp_id, _token : token},
		username : user_name,
		current_string : $(".user-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(),
		beforeSend: function( xhr ) {
			var img_html = "<img src='/img/loading/loading1.gif'>";
	    	$(".user-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(img_html);
	  	}
	})
	.done(function( data ) {
		if(data['error'] == 0){
			// If the relation did exist, delete it from the string of users
			if(data['data']['exist_relation']){
				var stringArr 		= this.current_string.split(', ');
				var newStringArr 	= Array();
				for(var i = 0; i < stringArr.length; i++){
					if(stringArr[i].trim() != this.username.trim()){
						newStringArr.push(stringArr[i]);
					}
				}
				var new_string = newStringArr.join(', ');
			}
			// If the relation did not exist, add it to the string of users
			else{

				var new_string = this.current_string+', '+this.username;
			}
			$(".user-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(new_string);
			showToast("Success","Your request has been completed",3); 
		}else{
			$(".user-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(this.current_string);
			showToast("Error","There was an error processing your request. Try again later.",-1); 
		}
	}).error(function(data){
		$(".user-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(this.current_string);
		showToast("Error","There was an error processing your request. Try again later.",-1); 
	});
}

