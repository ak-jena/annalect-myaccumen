$(function() {

	$("#advanced > div").not("."+$("select[name='source']").find(":selected").attr("data-source")).hide();

	$("select[name='source']").change(function(){
		var $this = $("select[name='source']");
		var c = $this.find(":selected").attr("data-source");

		console.log(c);

		$("#advanced > div").hide();
		$("#advanced > div."+c).show();
	});
});