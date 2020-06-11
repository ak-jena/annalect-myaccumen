$(document).ready(function(){
	
	$('.nav-tabs > li').click(function(){
		var role = $(this).data('role');

		$('.nav-tabs li').each(function(){
			$(this).removeClass('active');
		})
		$('.role-panel').addClass('hidden');

		$(this).addClass('active');
		$('#'+role).removeClass('hidden');
	});


	var where_str = $("input[name='where_str']").val();
	ajaxViewabilityChart(where_str);


});


function ajaxViewabilityChart(where_str){
	var token = $("input[name='_token']").val();
   	$.ajax({
   		type : 		"POST",
   		dataType : 	"json", 
		url : 		"/reports/viewability-chart-ajax",
		data : 		{ where_str : where_str, _token : token},
		beforeSend: function( xhr ) {
			$(".loading-buttons[t='viewability-chart']").css('display','inline');
	  	}
	})
	.done(function( data ) {
		$(".loading-buttons[t='viewability-chart']").css('display','none');
		if(data['error'] == 0){
			Morris.Area({
			    element: 'viewability-chart',
			    behaveLikeLine: false,
			    data: data['data'],
			    xkey: 'x',
			    ykeys: ['y'],
			    labels: ['Viewability (%)'],
			    lineColors:['#4EC9B4'],
			    pointSize: 4,
			    lineWidth: 1

			});
		}
		
	}).error(function(data){
		
	});
}

