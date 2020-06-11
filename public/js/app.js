$(document).ready(function(){
    
    $(".iCheck-helper").click(function(){
       var checked = $(this).parent("div").attr("aria-checked"); 
       
        $(this).parent("div").children("input").trigger('change');
    });
    
    // If there is a success message, toast it
    if($("input[name='success-toast']").length > 0){
            showSuccessMsg();
    }

    // tooltip functionality
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});

function showSuccessMsg(){
	var message = $("input[name='success-toast']").val();
	showToast("Success",message,3);
}

