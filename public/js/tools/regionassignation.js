$(document).ready(function(){
	$("select[name='regions[]']").select2({
		placeholder : "Select the regions",
		allowClear 	: true,
	});

	$("select[name='template']").select2();

	if($("input[name='success-msg']").length){
		var msg = $("input[name='success-msg']").val();
		showToast("Success",msg,3);
	}

	var action = $("input[name='action']:checked").val();
	showAction(action);
	$("input[name='action']").change(function(){
		var action = $("input[name='action']:checked").val();
		showAction(action);
	});

	var use_template = $("input[name='use-template']").is(':checked');
	showTemplate(use_template);
	$("input[name='use-template']").change(function(){
		var use_template = $(this).is(':checked');
		showTemplate(use_template);
	});
});

function showAction(action){
	if(action == "delete"){
		$("#regions-form-group").hide();
		$("#templates-form-group").hide();
	}else{
		$("#regions-form-group").show();
		$("#templates-form-group").show();
	}
}
function showTemplate(use_template){
	if(use_template){
		$("#form-template-select").removeClass('hidden');
		$("#regions-form-group").addClass('hidden');
	}else{

		$("#regions-form-group").removeClass('hidden');
		$("#form-template-select").addClass('hidden');
	}
}