$(document).ready(function(){
	$("select[name='template']").select2();
	$("select[name='regions[]']").select2({
		placeholder : "Select the regions",
		allowClear 	: true,
	});

	checkTemplateStatus($("select[name='template']").val());
	$("select[name='template']").change(function(){
		var value = $(this).val();
		checkTemplateStatus(value);
	});

	$("input[name='name']").keyup(function(){
		$("select[name='template'] option").each(function(){
			var name = $("input[name='name']").val();
			var template_list = $(this).text();
			console.log(name);
			console.log(template_list);
			if(template_list == name){
				console.log('equal');
				$("#form-section-name").addClass('has-error');
				$("#help-block-name").removeClass('hidden');
				$("button[name='create']").attr('disabled','disabled');
				return false;
			}else{
				console.log('different');
				$("#form-section-name").removeClass('has-error');
				$("#help-block-name").addClass('hidden');
				$("button[name='create']").removeAttr('disabled');
				if(name == ""){
					console.log('empty');
					$("button[name='create']").attr('disabled','disabled');
					return false;
				}
			}
		});
	});

	$("button[name='create']").click(function(e){
		e.preventDefault();
		$("button[name='create']").attr('disabled','disabled');
		$("form[name='form-template']").submit();
	});
});

function checkTemplateStatus(value){
	// value == 0 -> create template; else -> template selected (view)
	if(value == 0){
		$("button[name='create']").removeClass('hidden');
		$("form-button-view").addClass('hidden');
		$(".form-template-new").removeClass('hidden');
	}else{
		$("button[name='create']").addClass('hidden');
		$("#form-button-view").removeClass('hidden');
		$(".form-template-new").addClass('hidden');
		$("#form-button-view").attr('href','/admin/region-template/'+value);
	}
}