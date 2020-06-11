
/*$(".agency-cell").click(function(){
	var current_agency_id 	= $(this).data('agencyid');
	var advertiser_id		= $(this).data('advertiserid');
	var dsp_id		= $(this).data('dspid');
	var new_agency_id		= $("select[name='agency']").val();
	ajaxAssignAgency(current_agency_id,advertiser_id,dsp_id,new_agency_id);
});
*/

function ajaxAssignAgency(current_agency_id,advertiser_id,dsp_id,new_agency_id){

	var token 				= $("input[name='_token'").val();
	var current_agency_name = $(".agency-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html();

	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/admin/agency-advertiser/ajax-agency-assign",
		data : 		{current_agency_id : current_agency_id, advertiser_id : advertiser_id, dsp_id : dsp_id, new_agency_id : new_agency_id, _token : token},
		beforeSend: function( xhr ) {
			var img_html = "<img src='/img/loading/loading1.gif'>";
	    	$(".agency-cell[data-advertiserid='"+advertiser_id+"']").html(img_html);
	  	}
	})
	.done(function( data ) {
		if(data['error'] == 0){
			$(".agency-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(data['data']['agency_name']);
			$(".agency-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").data('agencyid',data['data']['agency_id']);
			showToast("Success","Your request has been completed",3); 
		}else{
			$(".agency-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(current_agency_name);
			showToast("Error","There was an error processing your request. Try again later.",-1); 
		}
	}).error(function(data){
		$(".agency-cell[data-advertiserid='"+advertiser_id+"'][data-dspid='"+dsp_id+"']").html(current_agency_name);
		showToast("Error","There was an error processing your request. Try again later.",-1); 
	});
}