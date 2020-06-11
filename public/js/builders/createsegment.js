$(document).ready(function(){
	$('.select2, .select2-multiple').select2();

	var checkin = $('.dpd1').datepicker({
		format : 'yyyy-mm-dd',
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
    	format : 'yyyy-mm-dd',
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
            checkout.hide();
    }).data('datepicker');
	

	/* Validations of data */
	$("input[name='segment-name']").change(function(){
		segmentNameValidator();
	});

	$("input[name='id']").change(function(){
		idValidator();
	});
});

function segmentNameValidator(){
	var element = $("input[name='segment-name']");
	var segment_name = element.val();
	var token 		= $("input[name='_token']").val();

	if(segment_name != ''){
		$.ajax({
	   		type : 		"POST",
	   		dataType : 	"json", 
			url : 		"/builders/create-segment-ajax-segment-name-validation/",
			data : 		{ segment_name : segment_name, _token : token },
			beforeSend: function( xhr ) {
		  	}
		})
		.done(function( data ) {
			//data = JSON.parse(data);
			if(data['error'] == 0){
				if(data['segment_exists'] == false){
					element.parent().removeClass('has-error').addClass('has-success');
					$("#label-segment-name").hide();
				}else{
					element.parent().removeClass('has-success').addClass('has-error');
					$("#label-segment-name").show().html('The Name already exists');
				}
			}else{
				
			}
		}).error(function(data){
			
		});
	}else{
		element.parent().removeClass('has-success').addClass('has-error');
		$("#label-segment-name").show().html('Introduce a segment name');
	}
}

function idValidator(){
	var element = $("input[name='id']");
	var str = element.val();
	console.log(str);
	if($.isNumeric(str)){
		element.parent().removeClass('has-error').addClass('has-success');
		$("#label-id").hide();
	}else{
		element.parent().removeClass('has-success').addClass('has-error');
		$("#label-id").show().html('The ID is not valid');
	}
}