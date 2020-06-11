$(document).ready(function(){
	$("select[name='campaign").select2({
		placeholder: "Select a campaign",
		allowClear: true
	});

	checkCampaignNameSelected($("select[name='campaign']").val());
	$("select[name='campaign']").change(function(){
		var value = $(this).val();
		checkCampaignNameSelected(value);
	});

	if($("input[name='error_msg']").length){
		var error_msg = $("input[name='error_msg']").val();
		showToast("Error",error_msg,-1); 
	}


	/* DatePicker */
	var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var checkin = $('.dpd1').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('.dpd2')[0].focus();
        }).data('datepicker');
    var checkout = $('.dpd2').datepicker({
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');
    
});

function checkCampaignNameSelected(value){
	if(value === 'other'){
		$("#form-element-new-campaign").show(250);
		$("#form-element-segment-info").html("-");
	}else if(value == ""){
		$("#form-element-new-campaign").hide(250);
		$("#form-element-segment-info").html("-");
	}else{
		$("#form-element-new-campaign").hide(250);
		ajaxGetSegmentInfo(value);
	}
}

function ajaxGetSegmentInfo(segment_id){
	var token = $("input[name='_token'").val();
	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/aimia/ajax-request-segment/",
		data : 		{segment_id : segment_id, _token : token},
		beforeSend: function( xhr ) {
			var img_html = "<img src='/img/loading/loading1.gif'>";
	    	$("#form-element-segment-info").html(img_html);
	  	}
	})
	.done(function( data ) {
		if(data['error'] == 0){
			console.log(data['data']);
			console.log(data['data']['segment_name']);
			var segment_name 	= data['data']['segment_name'];
			var segment_apn_id	= data['data']['segment_apn_id'];
			$("#form-element-segment-info").html(segment_name+" ("+segment_apn_id+")");
		}
	}).error(function(data){
		var error_msg_std = "Couldn't retrieve the segment information";
		$("#form-element-segment-info").html(error_msg_std);
	});
}