/*----------------------
 *  Toast Message
 *  
 *  Type Codes:
 *      * -1: Error
 *      * 1 : Info
 *      * 2 : Warning
 *      * 3 : Success
 *  
 */ 


function showToast(title,msg,type){
    var shortCutFunction;
    if(typeof(type) == 'string'){
        shortCutFunction = type;
    }else{
        switch(type){
            case 3 :
                shortCutFunction = 'success';
                break;
            case -1 :
                shortCutFunction = 'error';
                break;
            case 2 :
                shortCutFunction = 'warning';
                break;
            case 1 : 
                shortCutFunction = 'info';
                break;
            default :
                shortCutFunction = 'info';
                break;   
        }
    }
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          };

        $("#toastrOptions").text("Command: toastr["
            + shortCutFunction
            + "](\""
            + msg
            + (title ? "\", \"" + title : '')
            + "\")\n\ntoastr.options = "
            + JSON.stringify(toastr.options, null, 2)
        );

        var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
        $toastlast = $toast;
        if ($toast.find('#okBtn').length) {
            $toast.delegate('#okBtn', 'click', function () {
                alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
                $toast.remove();
            });
        }
        if ($toast.find('#surpriseBtn').length) {
            $toast.delegate('#surpriseBtn', 'click', function () {
                alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
            });
        }
}
