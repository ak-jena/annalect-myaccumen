var bar = $('.progress-bar');
var percent = $('.progress-bar');

$(document).ready(function(){

	$("#schedule-table").dataTable( {
		"order": []
	} );

	$("#file-input-segment").fileinput({
	    showUpload: false,
	    showCaption: false,
	    browseClass: "btn btn-primary btn-lg",
	    allowedFileExtensions : ['csv']
	});
	/*   
	$('#form-create-segment').ajaxForm({
	    beforeSend: function() {
	    	$(".kv-upload-progress").removeClass('hide');
	        var percentVal = '0%';
	        $('.progress-bar').width(percentVal);
	        $('.progress-bar').html(percentVal);
	    },
	    uploadProgress: function(event, position, total, percentComplete) {
	    	console.log(percentComplete);
	        var percentVal = percentComplete + '%';
	        $('.progress-bar').width(percentVal);
	        $('.progress-bar').html(percentVal);
	    },
	    success: function() {
	        var percentVal = '100%';
	        $('.progress-bar').width(percentVal);
	        $('.progress-bar').html(percentVal);
	    },
            complete: function(xhr) {
                data = JSON.parse(xhr.responseText);
                if(data['error'] === 0){
                    console.log(data['msg']);
                    showToast("Success",data['msg'],3); 	
                    $('.progress-bar').html("Complete");
                }else{
                    showToast("Error",data['msg'],-1); 	
                    $('.progress-bar').html("Error");
                }
            },
            error: function(){
                showToast("Error","There was an error with the request. Try again later",-1); 	
                $('.progress-bar').html("Error");
            }
	});
        */
	/* Date Picker for the end date (for the normal form and the reactivation form) */
	$("input[name='end-date']").datepicker({
		placeholder : 'Date format (yyyy-mm-dd)',
		format: 'yyyy-mm-dd'
	});

	/* Reactivate a segment or edit it */
	// Introduce information in the modal
	$("button[data-target='#reactivate-segment']").click(function(){
            var segment_id = $(this).data('segment');
            $("#modal-segment-id").val(segment_id);
	});
	
	// $('form').on('submit', uploadFiles);
});

// function uploadFiles(event){
// 	event.stopPropagation();
// 	event.preventDefault();
 
//  	var options = { 
//             target:   '#output', 
//             beforeSubmit:  beforeSubmit,
//             uploadProgress: OnProgress, //upload progress callback 
//             success:       afterSuccess,
//             resetForm: true  
//         };
//  	event.ajaxSubmit(options);
//  	return false;

// }