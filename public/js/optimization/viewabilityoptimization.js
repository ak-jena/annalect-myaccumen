$(document).ready(function(){
	$("input[name='viewability-target']").TouchSpin({
	    initval: 70,
	    postfix: '%'
	});

	/* Modal Optimization Code */
	// Info popover in the modal
	$("#modal-target-viewability-info").popover();

	// Change the Estimate number of placement and CPM
	$("input[name='viewability-target']").change(function(){
		ajaxEstimateViewability();
	});
	ajaxEstimateViewability();


	/* Modal show current active optimizations Code */
	$("#current-optimization-button").click(function(){
		ajaxShowCurrentOptimizations();
	});
	$("select[name='show-all-optimizations']").change(function(){
		ajaxShowCurrentOptimizations();
	});
});
/* When a checkbox is selected/deselected, save the information in hidden inputs, since the data-table destroy the checkboxes when unfilter */
function appendItem(value){
	var checked = $("input[name='line-item[]'][value='"+value+"']").prop('checked');
	if(checked){
		var str = $("input[name='line-item[]'][value='"+value+"']").data('lineitem');
		$("#schedule-table").append("<input type='hidden' name='line-item-selected[]' value='"+value+"' data-lineitem='"+str+"'>");
	}else{
		$("input[name='line-item-selected[]'][value='"+value+"']").remove();
	}
}

// When opening modal, show the selected LI
function modalItemsSelected()
{
	console.log('clicked');
	$("#modal-selected-li").html('');
	$("input[name='line-item-selected[]']").each(function(){
		var value = $(this).val();
		var str = $(this).data('lineitem');
		console.log(value);
		$("#modal-selected-li").append("<li>"+str+" ("+value+")</li>");
	});
}

/* Listener to delete an optimization */
function deleteOptimizationListener(){
	$(".delete-optimization").click(function(){
            var id = $(this).data('id');
            swal({   
                title: "Are you sure?",
                text: "This optimization will be deleted",
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Delete", 
                closeOnConfirm: false 
            }, 
            function(){
                    ajaxDeleteOptimization(id);
            });              
            
	});
}

function ajaxEstimateViewability(){
	var target_viewability = $("input[name='viewability-target']").val();
	var token = $("input[name='_token'").val();
	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/optimization/viewability-estimate-ajax/",
		data : 		{target_viewability : target_viewability, _token : token},
		beforeSend: function( xhr ) {
			var img_html = "<img src='/img/loading/loading1.gif'>";
	    	$("#modal-placements-num").html(img_html);
			$("#modal-average-cpm").html(img_html);
	  	}
	})
	.done(function( data ) {
		if(data['error'] == 0){
			var placements_num 	= data['data']['placements_num'];
			var cpm 			= data['data']['cpm'];
			$("#modal-placements-num").html(placements_num);
			$("#modal-average-cpm").html(cpm);
		}
	}).error(function(data){
		var error_msg_std = 'Error retrieving the data';
		$("#modal-placements-num").html(error_msg_std);
		$("#modal-average-cpm").html(error_msg_std);
	});
}

function ajaxShowCurrentOptimizations(){
	var token 		= $("input[name='_token']").val();
	var show_all 	= $("select[name='show-all-optimizations'] option:selected").val();
	console.log(show_all);
	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/optimization/viewability-active-ajax/",
		data : 		{ show_all : show_all, _token : token },
		beforeSend: function( xhr ) {
			var img_html = "<img src='/img/loading/loading1.gif'>";
	    	$("#current-optimization-modal-body").html(img_html);
	  	}
	})
	.done(function( data ) {
		//data = JSON.parse(data);
		if(data['error'] === 0){
			var html = "<label>These are the optimizations that are active</label>"+
						"<table class='table table-hover table-striped table-condensed table-bordered' id='current-optimization-modal-table'>"+
							"<tr>"+
								"<th>Line Item</th>"+
								"<th>Target Optimization</th>"+
								"<th>User</th>"+
								"<th>Date</th>"+
								"<th></th>"+
							"</tr>"+
						"</table>";

			$("#current-optimization-modal-body").html(html);
			$.each(data['data'],function(row,value){
				var row_danger = '';
				var delete_button;
				if(value['target_viewability'] === 0){
					row_danger 		= 'danger';
					delete_button 	= 'Pending to delete';
				}else{
					delete_button 	= "<span class='text-red fa fa-trash clickable delete-optimization' data-id='"+value['id']+"'>";
				}
				var html = "<tr class='active-optimizations-row "+row_danger+"' data-id='"+value['id']+"'>"+
								"<td>"+value['line_item_name']+"("+value['line_item_id']+")</td>"+
								"<td class='column-target' data-id='"+value['id']+"''>"+value['target_viewability']+"</td>"+
								"<td>"+value['user_id']+"</td>"+
								"<td>"+value['record_date']+"</td>"+
								"<td class='column-trash' data-id='"+value['id']+"'>"+delete_button+"</td>"+
							"</tr>";
				$("#current-optimization-modal-table").append(html);
			});
			deleteOptimizationListener();
		}else{
			var error_msg_std = 'Error retrieving data';
			$("#current-optimization-modal-body").html(error_msg_std);
		}
	}).error(function(data){
		var error_msg_std = 'Error retrieving data';
		$("#current-optimization-modal-body").html(error_msg_std);
	});
        
        
}

function ajaxDeleteOptimization(id){
	var token 		= $("input[name='_token']").val();
	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/optimization/viewability-delete-ajax/",
		data : 		{ id : id, _token : token },
		beforeSend: function( xhr ) {
	    	$(".delete-optimization[data-id='"+id+"']").removeClass('fa-trash').addClass('fa-spinner').addClass('fa-spin');
	  	}
	})
	.done(function( data ) {
		//data = JSON.parse(data);
		if(data['error'] == 0){
			var html = "<span class='fa fa-thumbs-up'>";
			$(".column-trash[data-id='"+id+"']").html(html);
			$(".column-target[data-id='"+id+"']").html('0');
			$(".active-optimizations-row[data-id='"+id+"']").addClass('danger');
			//showToast("Success",'Optimization will be deleted soon',3);
                        swal("Deletion in progress!", "Optimization will be removed soon", "success");
		}else{
			$(".delete-optimization[data-id='"+id+"']").removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-trash');
			//showToast("Error",'There was an error. Try again',-1);
                        swal("Oops!", "There was an error, please try again later.", "error");
		}
	}).error(function(data){
		$(".delete-optimization[data-id='"+id+"']").removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-trash');
		//showToast("Error",'There was an error. Try again',-1);
                swal("Oops!", "There was an error, please try again later.", "error");
	});
}
	