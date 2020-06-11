		/* Select 2 functionality */
		var advertiser_selectize = $('#advertiser-list-select').selectize({
			placeholder: "Select an advertiser",
			create: false,
			optgroup: true,
		});
		var adv_selectize = advertiser_selectize[0].selectize;

		// $('input[name="line-item"]').selectize({
		// 	plugins: ['remove_button'],
		// 	placeholder: "Line Items",
		// 	delimiter: ',',
		// 	persist: false,
		// 	create:function(input){
		// 		return {
		// 			value: input,
		// 			text: input
		// 		}
		// 	}
		// });
		$('#input-email').selectize({
			plugins: ['remove_button'],
			placeholder: "Email Address",
			delimiter: ',',
			persist: false,
			create:function(input){
				return {
					value: input,
					text: input
				}
			}
		});

		/* Custom date selector functionality */
		$('#schedule-length-add').change(function(){
			if($(this).val() === 'custom'){
				$('#custom-date-form').show(250);
			}else{
				$('#custom-date-form').hide(250);
			}
		});
		$('.date-picker').datepicker({
			format: "yyyy-mm-dd"
		});
		if($('#schedule-length-add').val() === 'custom'){
			$('#custom-date-form').show();
		}		

		// Adding more Insertion Orders and Line Items from different DSPs
		$('.dsp-button').click(function(event){
			event.preventDefault();
			var target = $(this).attr('target');
			var dsp = $('select[name="'+target+'-dsp"]').val();
			var dsp_name = $('select[name="'+target+'-dsp"').children('option:selected').text();
			var ids = $('input[name="'+target+'"]').val();

			$('#'+target+'-dsp-selected').show(250);
			$('#'+target+'-dsp-selected').children('table').append('<tr><input type="hidden" name="'+target+'-id-list[]" value="'+ids+'"><input type="hidden" name="'+target+'-dsp-list[]" value="'+dsp+'"><td>'+ids+'</td><td>'+dsp_name+'</td></tr>');
			console.log('dsp: '+dsp);
			console.log('ids: '+ids);
			console.log('dsp name: '+dsp_name);
		});

		// Scheduler status tooltip
		$(".scheduler-status").tooltip();

		// Schedule Modal
	   $("#modal-schedule-trigger").click(function(){
	   		$("#modal-schedule").modal('show');
	   });
	   // Schedule frequency form
	   $("#schedule-frequency").on('change',function(){
	   		if($(this).val() == 'w'){
	   			$("#form-group-day-week").css('display','block');
	   			$("#form-group-day-month").css('display','none');
	   		}else if($(this).val() == 'm'){
	   			$("#form-group-day-week").css('display','none');
	   			$("#form-group-day-month").css('display','block');
	   		}else{
	   			$("#form-group-day-week").css('display','none');
	   			$("#form-group-day-month").css('display','none');
	   		}
	   });

	   /* Show and hide action buttons in the scheduler table */
	   $('#schedule-table').children('tbody').children('tr').hover(
	   	function(){
	   		var row_id = $(this).data('id');
	   		$('.schedule-table-action-buttons[data-id="'+row_id+'"]').css('visibility','visible');;
	   },function(){
	   		var row_id = $(this).data('id');
	   		$('.schedule-table-action-buttons[data-id="'+row_id+'"]').css('visibility','hidden');;
	   });

	   /* Show the download button in the scheduler table */
	   $('#schedule-table').children('tbody').children('tr').hover(function(){
	   		var id = $(this).data('id');
	   		$(".column-id[number='"+id+"']").hide();
	   		$(".column-download[number='"+id+"']").show();
	   },function(){
	   		var id = $(this).data('id');
	   		$(".column-id[number='"+id+"']").show();
	   		$(".column-download[number='"+id+"']").hide();
	   });
	   /* Download button submits the form */
	   $(".column-download").click(function(){
	   		var id = $(this).attr('number');
	   		var input = $('<input>').attr('type','hidden').attr('name','report-scheduled-download').val(id);
	   		$("form[name='scheduled-form']").append($(input));
	   		$("form[name='scheduled-form']").submit();
	   });

	   /* Edit Scheduler - Custom data length */
	   $('#schedule-length[t="data-length"]').change(function(){
	   		var scheduleid = $(this).attr('scheduleid');
	   		var value = $(this).val();
	   		console.log(scheduleid);
	   		console.log(value);
	   		if($(this).val() === 'custom'){
				$('.custom-date-form-edit[scheduleid="'+scheduleid+'"]').show(250);
			}else{
				$('.custom-date-form-edit[scheduleid="'+scheduleid+'"]').hide(250);
			}
	   })
	   if($('#schedule-length[t="data-length"]').val() === 'custom'){
			$('.custom-date-form-edit').show();
		}
	   // Disable submit button onClick to prevent double submitting
	   // $('.btn-submit-spending').click(function(){
	   // 		$(this).attr('disabled',true);
	   // 		$("#form-spending").submit();
	   // });

		/* Let the people select ONLY 1 option: Advertiser or Insertion Order or Line Item */
		advertiserEnabler();
		$("select[name='advid']").change(function(){
			advertiserEnabler();
		});
		ioEnabler(adv_selectize);
		$("input[name='insertion-order']").change(function(){
			ioEnabler(adv_selectize);
		});
		liEnabler(adv_selectize);
		$("input[name='line-item']").change(function(){
			liEnabler(adv_selectize);
		});

		function advertiserEnabler(){
			var selected_advertiser = $("select[name='advid']").val();
			console.log(selected_advertiser);
			// If there a selected advertiser, disable the other fields and reset them
			if(!selected_advertiser.trim()){
				$("input[name='insertion-order']").removeAttr('disabled');
				$("input[name='line-item']").removeAttr('disabled');
			}else{
				$("input[name='insertion-order']").attr('disabled','disabled');
				$("input[name='line-item']").attr('disabled','disabled');
			}
		}

		function ioEnabler(adv_selectize){
			var selected_io = $("input[name='insertion-order']").val();
			if(!selected_io.trim()){
				selected_io = $("input[name='insertion-order-id-list[]'").val();
			}
			console.log(selected_io);
			// If there a selected advertiser, disable the other fields and reset them
			if(selected_io == undefined){
				adv_selectize.enable();
				$("input[name='line-item']").removeAttr('disabled');
			}else{

				adv_selectize.disable();
				$("input[name='line-item']").attr('disabled','disabled');
			}
		}

		function liEnabler(adv_selectize){
			var selected_li = $("input[name='line-item']").val();
			if(!selected_li.trim()){
				selected_li = $("input[name='line-item-id-list[]'").val();
			}
			console.log(selected_li);
			// If there a selected advertiser, disable the other fields and reset them
			if(selected_li == undefined){
				$("input[name='insertion-order']").removeAttr('disabled');
				adv_selectize.enable();
			}else{
				$("input[name='insertion-order']").attr('disabled','disabled');
				adv_selectize.disable();
			}
		}

	   	/* Update process */
	   	$(".schedule-info").dblclick(function(){
	   		var type = $(this).attr('type');
	   		var scheduler_id = $(this).attr('scheduleid');
	   		$(this).css('display','none');
	   		$(".edit-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','inline');
	   		$(".schedule-field[type='"+type+"'][scheduleid="+scheduler_id+"]").css('display','block');
	   	});
	   	$(".schedule-cancel").click(function(){
	   		var type = $(this).attr('type');
	   		var scheduler_id = $(this).attr('scheduleid');
	   		$(".schedule-field[type='"+type+"'][scheduleid="+scheduler_id+"]").css('display','none');
	   		$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").css('display','block');
	   	});
	   	$(".schedule-update").click(function(){
	   		var type = $(this).attr('type');
	   		var scheduler_id = $(this).attr('scheduleid');
	   		// Get data from input text
	   		var data = $(".schedule-update-form[t='"+type+"'][scheduleid="+scheduler_id+"]").val();
	   		// If there is not data, check if it is a selected option from dropdown
	   		if(!data){
	   			data = $(".schedule-update-form[t='"+type+"'][scheduleid="+scheduler_id+"]").find(":selected").text();
	   		}
	   		// If is not a dropdown option, check the values of the checkboxes
	   		if(!data){
	   			data = $('input[scheduleid='+scheduler_id+'][name="schedule-day-week[]"]:checked').map(function() {return this.value;}).get().join('');
	   		}
	   		// If type is data-length and the length is custom, get the start date and end date
	   		if(type == 'data-length' && data == 'custom'){
	   			var dataArr = new Object();
	   			dataArr.data = data;
	   			dataArr.start_date = $(".custom-date-form-edit[scheduleid="+scheduler_id+"]").children('input[name="start-date"]').val();
	   			dataArr.end_date = $(".custom-date-form-edit[scheduleid="+scheduler_id+"]").children('input[name="end-date"]').val();
	   			data = dataArr;
	   		}
	   		ajaxUpdate(scheduler_id,type,data);
	   	});

	   	function ajaxUpdate(scheduler_id,type,data){
	   		var token = $("input[name='_token'").val();
		   	$.ajax({
		   		type : 		"POST",
		   		dataType : 	"json", 
				url : 		"/reports/spending/",
				data : 		{event_type : 'update', scheduler_id : scheduler_id, type : type, data : JSON.stringify(data), _token : token},
				beforeSend: function( xhr ) {
			    	$(".edit-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','none');
			    	$(".loading-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','inline');
			  	}
			})
			.done(function( data ) {
				console.log(data);
				if(data['error'] == 0){
					$("#ajax-success-alert").html(data['msg']);
					$("#ajax-success-alert").show().delay(5000).fadeOut();
					$(".loading-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','none');
					
					$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").html(data['updated_value'].replace(/\,/g, ' '));
					if(type == 'frequency'){
						var freq_map = {d : "Daily", w : "Weekly", m : "Monthly"};
						$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").html(freq_map[data['updated_value']]);
					}else if(type == 'data-length'){
						$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").html(data['updated_value'].replace('-',' '));
						$('.start-date-row[scheduleid="'+scheduler_id+'"]').html(data.start_date);
						$('.end-date-row[scheduleid="'+scheduler_id+'"]').html(data.end_date);
					}else if(type == 'day-week'){
						var freq_map = Array('','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
						var dataArr = data['updated_value'].split('')
						var strArr = Array();
						$.each(dataArr,function(index,value){
							strArr.push(freq_map[value]);
						});
						$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").html(strArr.join(', '));
					}
					if(type == 'frequency' || type == "day-month" || type == "data-length"){
						$("#schedule-"+type+"[value='"+data['uploaded_value']+"']").attr("selected","selected");
					}
	   				$(".schedule-field[type='"+type+"'][scheduleid="+scheduler_id+"]").css('display','none');
	   				$(".schedule-info[type='"+type+"'][scheduleid="+scheduler_id+"]").css('display','block');
					console.log('no_error');
				}else{
					$("#ajax-error-alert").html(data['msg']);
					$("#ajax-error-alert").show().delay(5000).fadeOut();
					$(".loading-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','none');
					$(".edit-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','inline');
					console.log('error');
				}
			}).error(function(data){
				$("#ajax-error-alert").html('There was a server error when making the petition');
				$("#ajax-error-alert").show().delay(5000).fadeOut();
				$(".loading-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','none');
				$(".edit-buttons[t='"+type+"'][scheduleid='"+scheduler_id+"']").css('display','inline');
			});
		}