$(document).ready(function(){
	$('.dsp-button').click(function(event){
		event.preventDefault();
		var target = $(this).attr('target');
		var dsp = $('select[name="'+target+'-dsp"]').val();
		var dsp_name = $('select[name="'+target+'-dsp"').children('option:selected').text();
		var ids = $('input[name="'+target+'"]').val();

		if(ids > 0){
			$('#'+target+'-dsp-selected').show(250);
			$('#'+target+'-dsp-selected').children('table').append('<tr name="'+target+'" value="'+ids+'" dsp="'+dsp+'">'+
					'<input type="hidden" name="'+target+'-id-list[]" value="'+ids+'">'+
					'<input type="hidden" name="'+target+'-dsp-list[]" value="'+dsp+'">'+
					'<td>'+ids+'</td><td>'+dsp_name+'</td><td><span class="glyphicon glyphicon-remove element-remove" data-target="'+target+'" data-value="'+ids+'" data-dsp="'+dsp+'"></td>'+
				'</tr>');
			console.log('dsp: '+dsp);
			console.log('ids: '+ids);
			console.log('dsp name: '+dsp_name);
			
			removeDspElement();
		}
	});


});

function removeDspElement(){
	$(".element-remove").click(function(){
		console.log('remove');
		var target 	= $(this).data('target');
		var ids 	= $(this).data('value');
		var dsp 	= $(this).data('dsp');
		$('tr[name="'+target+'"][value="'+ids+'"][dsp="'+dsp+'"]').remove();
	});
}

