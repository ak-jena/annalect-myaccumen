/**
 * Created by norman.tong on 17/08/2016.
 */
$(document).ready(function(){
    $("select[name='advertiser']").change(function(){
        $("form[name='choose-advertiser']").submit();
    });

    $(".editable").dblclick(function(){
        var text = $(this).text();
        var html = renderInputCreative(text);
        $(this).removeClass('editable');
        $(this).html(html);

        editCallableFunctions();

    });

});

function validateCreativeName(name){
    var creative = new RegExp('^[0-9]*_.*$');
    return creative.test(name);
}

function renderInputCreative(text){
    return "<div class='form-group has-feedback'>" +
    "<input type='text' class='form-control' name='creative-name' value='"+text+"' maxlength='100' style='min-width:300px'>" +
    "<span class='glyphicon glyphicon-ok form-control-feedback hidden' aria-hidden='true'></span>" +
    "<button class='btn btn-primary' name='save-creative-name' disabled>Save</button>" +
    "</div>";
}

function editCallableFunctions(){
    $("input[name='creative-name']").keyup(function(){
        var name = $(this).val();
        if(validateCreativeName(name)){
            $(this).parent('.has-feedback').addClass('has-success');
            $(this).parent('.has-feedback').removeClass('has-error');
            $(this).parent('.has-feedback').children('.form-control-feedback').removeClass('hidden');
            $(this).parent('.has-feedback').children('button').removeAttr('disabled');
        }else{
            $(this).parent('.has-feedback').addClass('has-error');
            $(this).parent('.has-feedback').removeClass('has-success');
            $(this).parent('.has-feedback').children('.form-control-feedback').addClass('hidden')
            $(this).parent('.has-feedback').children('button').attr('disabled','disabled');
        }
    });

    $("button[name='save-creative-name']").click(function(){
        var creative_id = $(this).closest('tr').data('creativeid');
        var creative_name = $(this).parent('.has-feedback').children("input[name='creative-name']").val();
        ajaxUpdateCreativeName(creative_id,creative_name);
    });
}


function ajaxUpdateCreativeName(creative_id,creative_name){
    var token 	= $("input[name='_token']").val();
    $.ajax({
        type : 		"POST",
        dataType : 	"json",
        url : 		"/tools/creatives-format-tracker",
        data : 		{ creative_id : creative_id, creative_name : creative_name, _token : token },
        beforeSend: function( xhr ) {
            var img_html = "<img src='/img/loading/loading1.gif'>";
            $("tr[data-creativeid='"+creative_id+"']").children('.new-creative-name').html(img_html);
        }
    })
        .done(function( data ) {
            //data = JSON.parse(data);
            if(data['error'] == false){
                $("tr[data-creativeid='"+creative_id+"']").children('.new-creative-name').addClass('editable');
                $("tr[data-creativeid='"+creative_id+"']").children('.new-creative-name').html(creative_name);
                showToast("Success","Data updated correctly",3);
            }else{
                $("tr[data-creativeid='"+creative_id+"']").children('.new-creative-name').html(renderInputCreative(creative_name));
                editCallableFunctions();
                showToast("Error","There was an error processing your request. Try again later.",-1);
            }
        }).error(function(data){
        $("tr[data-creativeid='"+creative_id+"']").children('.new-creative-name').html(renderInputCreative(creative_name));
        editCallableFunctions();
        showToast("Error","There was an error processing your request. Try again later.",-1);
    });
}