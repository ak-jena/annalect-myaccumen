
$(document).ready(function(){
	$("select[name='weather-list']").select2();

	var url_id = $("select[name='weather-list']").val();
	$("a[name='show-detailed-list']").attr('href','/builders/weather/'+url_id);
	$("select[name='weather-list']").change(function(){
		var id = $(this).val();
		$("a[name='show-detailed-list']").attr('href','/builders/weather/'+id);
	})


	/* Open the modal to create a new weather list */
	$("#modal-create-weather-trigger").click(function(event){
		event.preventDefault();
		event.stopPropagation();
		$("#modal-create-weather").modal('show');
	});

	/* Form wizard to create a new weather list */
	var form = $("#create-weather-target-form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        }
    });
    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            submitWeatherForm();
        }
    });

    /* Name of the new list validation */
    $("input[name='list-name']").keyup(function(){
    	var name = $(this).val();
    	ajaxValidateListName(name);
    });
    $("input[name='list-name']").change(function(){
    	var name = $(this).val();
    	ajaxValidateListName(name);
    });

    $("a[href='#next']").click(function(){
    	fillValidationData();
	});

    // Toast message
    if($("input[name='msg-type']").length > 0){
		var msg_type = $("input[name='msg-type']").val();
		var msg_text = $("input[name='msg-text']").val();
		showToast("",msg_text,parseInt(msg_type)); 
	}
});


function fillValidationData(){
	var listname 				= $("input[name='list-name']").val();
	var precipitation_action	= $("select[name='precipitation-action'] option:selected").text();
	var precipitation_type		= $("select[name='precipitation-type'] option:selected").text();
	var temperature_action 		= $("select[name='temperature-action'] option:selected").text();
	var temperature_value 		= $("input[name='temperature-value'").val();
	var cloud_action 			= $("select[name='cloud-action'] option:selected").text();
	var cloud_value 			= $("input[name='cloud-value'").val();
	var campaign_id 			= $("textarea[name='campaign-id']").val();
	var target_specific_region  = $("select[name='target-specific-regions'] option:selected").text();
	var target_specific_region_val = $("select[name='target-specific-regions']").val();
	var end_date 				= $("input[name='end-date']").val();
	if(target_specific_region_val == 1){
		var target_regions 		= $("select[name='target-regions[]'] option:selected").text();
	}else if(target_specific_region_val == 2){
		var target_regions 		= $("select[name='copy-regions'] option:selected").text();
	}else{
		var target_regions 		= "";
	}
	console.log(target_specific_region_val);
	

	campaign_id = campaign_id.replace('/[\n]/g',',');

	var html = "<li>List Name : "+listname+"</li>"+
				"<li>Precipitation : "+precipitation_action+" "+precipitation_type+"</li>"+
				"<li>Temperature : "+temperature_action+" "+temperature_value+"</li>"+
				"<li>Cloud : "+cloud_action+" "+cloud_value+"</li>"+
				"<li>End Date : "+end_date+"</li>"+
				"<li>Campaign IDs : "+campaign_id+"</li>"+
				"<li>Target : "+target_specific_region+"</li>"+
				"<li>Regions : "+target_regions+"</li>";

	$("#validation-data-list").html(html);	
}

function submitWeatherForm(){
	$("#a[name='finish']").addClass('disabled');
	$("#create-weather-target-form").submit();
}

function ajaxValidateListName(name){
	var token 		= $("input[name='_token']").val();
	var element 	= $("input[name='list-name']")
	if(name != ''){
		$.ajax({
	   		type : 		"POST",
	   		dataType : 	"json", 
			url : 		"/builders/weather/ajax-validate-list-name",
			data : 		{ name : name, _token : token },
			beforeSend: function( xhr ) {
				element.addClass('spinner');
		  	}
		})
		.done(function( data ) {
			//data = JSON.parse(data);
			element.removeClass('spinner');
			if(data['error'] == 0){
				if(data['name_exists'] == false){
					element.parent().removeClass('has-error-custom').addClass('has-success-custom');
					$("#label-error-name").hide();
				}else{
					element.parent().removeClass('has-success-custom').addClass('has-error-custom');
					$("#label-error-name").show().html('The Name already exists');
				}
			}else{
				
			}
		}).error(function(data){
			
		});
	}else{
		element.parent().removeClass('has-success-custom').addClass('has-error-custom');
		$("#label-error-name").show().html('Introduce a list name');
	}
}
