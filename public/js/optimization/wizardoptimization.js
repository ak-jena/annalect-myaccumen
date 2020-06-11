$(document).ready(function(){

	/* Form wizard to create a new optimization */
	// var form = $("#create-optimization-form");
    // form.validate({
     //    errorPlacement: function errorPlacement(error, element) {
     //        element.after(error);
     //    }
    // });
    // form.children("div").steps({
     //    headerTag: "h3",
     //    bodyTag: "section",
     //    transitionEffect: "slideLeft",
     //    onStepChanging: function (event, currentIndex, newIndex) {
     //        form.validate().settings.ignore = ":disabled,:hidden";
     //        return form.valid();
     //    },
     //    onFinishing: function (event, currentIndex) {
     //        form.validate().settings.ignore = ":disabled";
     //        return form.valid();
     //    },
     //    onFinished: function (event, currentIndex) {
     //        submitWizardForm();
     //    }
    // });
    //
    // $("a[href='#next']").click(function(){
    	// fillValidationData();
	// });

    /* Edit parameters capability */
    $(".editable").dblclick(function(){
        console.log('dbclick');
        $(this).children('.parameter-value').hide();
        $(this).children('.parameter-edit').show();
        $(this).removeClass('editable');
    });

    $(".parameter-cancel").click(function(){
        $(this).closest('td').children('.parameter-value').show();
        $(this).closest('td').children('.parameter-edit').hide();
        $(this).closest('td').addClass('editable');
    })

    $(".parameter-update").click(function(){
        var line_item   = $(this).closest('tr').data('lineitemid');
        var parameter   = $(this).closest('td').data('parameter');
        var previous_val= $(this).closest('td').children('.parameter-value').html();
        if(parameter == 'safety-pacing'){
            var value   = $(this).closest('.parameter-edit').find("select[name='deactivate-safety-pacing'] :selected").val();
        }else{
            var value   = $(this).closest('.parameter-edit').find('input').val();
        }
        console.log(value);
        ajaxUpdateParameter(line_item,parameter,value,previous_val);
    });

    $("input[name='viewability-target-update']").TouchSpin({
        initval: 55,
        postfix: '%'
    });

    // Toast message
    if($("input[name='msg-type']").length > 0){
        var msg_type = $("input[name='msg-type']").val();
        var msg_text = $("input[name='msg-text']").val();
        showToast("",msg_text,parseInt(msg_type));
    }

});

function fillValidationData(){
	var line_item 			= $("select[name='optimize-line-items']  option:selected").text();
	var target_viewability 	= $("input[name='viewability-target']").val();
	var target_cpm 			= $("input[name='cpm-target']").val();
	var deactivate_pacing 	= $("input[name='deactivate-pacing']").is(':checked');
    console.log(deactivate_pacing);
	if(deactivate_pacing ){
		pacing_str = 'yes';
	}else{
		pacing_str = 'no';
	}
	

	// campaign_id = campaign_id.replace('/[\n]/g',',');

	var html = "<li>Line Items : "+line_item+"</li>"+
				"<li>Target Viewability : "+target_viewability+"%</li>"+
				"<li>Target CPM : "+target_cpm+"</li>"+
				"<li>Deactivate Safety Pacing : "+pacing_str+"</li>";

	$("#validation-data-list").html(html);	
}

/* Submit the Wizard Optimization form */
function submitWizardForm(){
    $("#a[name='finish']").addClass('disabled');
    $("#create-optimization-form").submit();
}

/* Updates a parameter from the optimization */
function ajaxUpdateParameter(line_item,parameter,value,previous_value){
    var token = $("input[name='_token'").val();
    var previous_param =
    $.ajax({
        type :      "POST",
        dataType :  "json", 
        url :       "/optimization/wizard-update-param-ajax/",
        data :      { line_item : line_item, parameter : parameter, value : value, _token : token },
        beforeSend: function( xhr ) {
            var img_html = "<img src='/img/loading/loading1.gif'>";
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').html(img_html);
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').show();
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-edit').hide();
        }
    })
    .done(function( data ) {
        if(data !== null && data['error'] == 0){
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').html(data['new_val']);
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").addClass('editable');
            //showToast("Success",data['msg'],3);
            swal("Success!", data['msg'], "success");
        }else{
            var error_msg_std = 'The data was not updated, try again later';
            //showToast("Error",error_msg_std,-1);
            swal("Oops!", error_msg_std, "error");
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').html(previous_value);
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').hide();
            $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-edit').show();
        }
    }).error(function(data){
        var error_msg_std = 'The data was not updated, try again later';
        //showToast("Error",error_msg_std,-1);
        swal("Oops!", error_msg_std, "error");
        $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').html(previous_value);
        $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-value').hide();
        $("tr[data-lineitemid='"+line_item+"']").children("td[data-parameter='"+parameter+"']").children('.parameter-edit').show();

    });
}

/* Show the Wizard */
function showWizard(){
    var token = $("input[name='_token']").val();
    $.ajax({
        type :      "POST",
        dataType :  "html",
        url :       "/optimization/wizard-show/",
        data :      { _token : token },
        beforeSend: function( xhr ) {
            var img_html = "<img src='/img/loading/loading1.gif'>";
            $("#section-wizard-steps").show().html(img_html);
        }
    })
        .done(function( data ) {
            $("#section-wizard-steps").hide();
            $("#section-wizard-steps").html(data);
            $("#section-wizard-steps").slideDown(1000);
        }).error(function(data){


    });
}

