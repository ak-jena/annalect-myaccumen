

/* Draggable functionality */
$(".window-right-list-element").draggable({
    revert: "invalid",
    cursor: "move",
    helper: function() {
      var selected = $('.active-drag');
      if (selected.length === 0) {
        selected = $(this);
      }
      var right_ids = new Array();
      $.each(selected,function(index,value){
            console.log(value.attributes['1'].value);
            right_ids.push(value.attributes['1'].value);
        });
        console.log(right_ids);
        
        var right_id = right_ids.join(',');
        $("#dragged_id").val(right_id);
      var container = $("<div />").attr('id', 'draggingContainer');
      container.attr('data-id',right_id);
      container.append(selected.clone());
      return container;
    },
    // start: function(event,ui) {
    //     var container_width = $("#draggingContainer").width();
    //     var container_height = $("#draggingContainer").height();
    //     ui.draggable.animage({top:container_height,left:container_height});
        
    // }
});

/* Droppable functionality */
droppableFunction();
function droppableFunction(){
    $(".window-left-list-element").droppable({
        over: function (event,ui){
            $(this).addClass('window-right-list-element-over');
        },
        out: function (event,ui){
            $(this).removeClass('window-right-list-element-over');
        },
        drop: function (event, ui) {
            $(this).removeClass('window-right-list-element-over');
            var droppedObject = ui.draggable;
            var droppedObjectid = ui.draggable['0'].attributes['0'].value; // get object type
            var left_id = $(this).attr('left_list');
            console.log(left_id);
            var dragged_id = $("#dragged_id").val();
            console.log(dragged_id);

            var value = $('#create-left-list-text').val();
            var pathname = window.location.pathname;
            var token = $("input[name='_token'").val();

            ajaxDropElements(pathname,left_id,dragged_id,token);
        }
    });
}

/* Edit and delete buttons */
showEditButtons();
function showEditButtons(){
    var html = '<span class="fa fa-pencil edit-button" data-toggle="modal" data-target="#windowModal" /><span class="fa fa-trash delete-button"  data-toggle="modal" data-target="#windowModal" /> <span class="fa fa-clone copy-button"  data-toggle="modal" data-target="#windowModal" />';
    var editClass = ".edit-buttons";
    $(editClass).html(html);
    $(editClass).css('display','none');
    $(editClass).parent('li').hover(function(){
       $(this).children('span').toggleClass('edit-buttons-display');
    });
    $('.edit-button').click(function(){
        var parent_id = $(this).parent('span').parent('li').attr('left_list');
        $('.window-action-panel').addClass('window-hidden');
        $('#modal-value-id').val(parent_id);
        $('#modal-value-type').val('edit-left-list');
        $('#modal-edit-panel').removeClass('window-hidden');
    });
    $('.delete-button').click(function(){
        var checkboxesArr = Array();
        var parent_id = $(this).parent('span').parent('li').attr('left_list');
        
        $('.window-action-panel').addClass('window-hidden');
        $('#modal-value-id').val(parent_id);
        
        /* Check if the left list has children selected */
        $('input[parent_id="'+parent_id+'"]:checked').each(function(){
            checkboxesArr.push($(this).attr('child_id'));
        });
        console.log(checkboxesArr);
        /* If there are selected children, those will be deleted. If not, the parent will be deleted with all its children */
        if(checkboxesArr.length > 0){
            $('#modal-value-id-sub').val(checkboxesArr.join(','));
            $('#modal-value-type').val('delete-left-list-element');
            $('#modal-delete-element-panel').removeClass('window-hidden');
        }else{
            $('#modal-value-type').val('delete-left-list');
            $('#modal-delete-panel').removeClass('window-hidden');
        }
    });
    $('.copy-button').click(function(){
        var parent_id = $(this).parent('span').parent('li').attr('left_list');
        $('.window-action-panel').addClass('window-hidden');
        $('#modal-value-id').val(parent_id);
        $('#modal-value-type').val('copy-left-list');
        $('#modal-copy-panel').removeClass('window-hidden');
    });
}
$('#modal-edit-submit').click(function(){
        var action = $('#modal-value-type').val();
        var left_id = $('#modal-value-id').val();
        var left_id_sub = $('#modal-value-id-sub').val();
        var pathname = window.location.pathname;
        var token = $("input[name='_token'").val();
        var value;
        if(action === 'edit-left-list'){
            value = $('#modal-edit-text').val();
        }else if(action === 'copy-left-list'){
            value = $('#modal-copy-text').val();
        }
        ajaxEditAction(pathname,action,left_id,left_id_sub,value,token);
    });

/* Right list selector */
$(".window-right-list-element").click(function () {
    if ($(this).hasClass('active-drag')) {
        $(this).removeClass('active-drag');
    } else {
        $(this).addClass('active-drag');
    }
});

/* Create a new element in the left list */
$('#create-left-list').click(function () {
    //$(this).prop('disabled', true);
    var $btn = $(this).button('loading');
    var value = $('#create-left-list-text').val();
    var pathname = window.location.pathname;
    var token = $("input[name='_token'").val();
    ajaxCreateLeftList(pathname, value, token);
});

/* Expand Collapse Treeview */
expandFunctions();
function expandFunctions(){
    $('#window-left-list').on('click','.window-left-list-icon',function(){
        var left_list_id = $(this).attr('left_list');
        var $list_selector = $('.window-left-list-element[left_list="'+left_list_id+'"]');
        if($list_selector.hasClass('window-left-sublist-shown')){
            $(this).attr('src','/img/details_open.png');
            $list_selector.removeClass('window-left-sublist-shown');
            $list_selector.addClass('window-left-sublist-hidden');
        }else{
            $(this).attr('src','/img/details_close.png');
            $list_selector.removeClass('window-left-sublist-hidden');
            $list_selector.addClass('window-left-sublist-shown');
        }
        $list_selector.children('ul').toggle(250);
    });
    $('.expand-buttons').click(function(){
        var action = $(this).attr('data-action');
        console.log(action);
        if(action === 'expand-all'){
            $('.window-left-list-element').each(function(){
                var element_id = $(this).attr('left_list');
                $('.window-left-list-icon[left_list="'+element_id+'"]').attr('src','/img/details_close.png');
                $(this).removeClass('window-left-sublist-hidden');
                $(this).addClass('window-left-sublist-shown');
                $(this).children('ul').show(250);
            });
        }else if(action === 'collapse-all'){
            $('.window-left-list-element').each(function(){
                var element_id = $(this).attr('left_list');
                $('.window-left-list-icon[left_list="'+element_id+'"]').attr('src','/img/details_open.png');
                $(this).removeClass('window-left-sublist-shown');
                $(this).addClass('window-left-sublist-hidden');
                $(this).children('ul').hide(250);
            });
        }
    });
}


/* Filtering lists */
$('#window-filter-left-list').keyup(function(){
    var name = $(this).val();
    filterList('left',name);
});

$('#window-filter-right-list').keyup(function(){
    var name = $(this).val();
    filterList('right',name);
});


function filterList(list,name){
    switch(list){
        case "left":
            list = $('.window-left-list-element');
            break;
        case "right":
            list = $('.window-right-list-element');
            break;
    }
    $.each(list,function(index,value){
        var namecmp = value.getAttribute('list_value');
        var element_id = value.getAttribute('left_list');
        if(namecmp.toLowerCase().indexOf(name.toLowerCase()) >= 0){
            value.setAttribute('style','display:block;');
            if(element_id > 0)
                $(".window-left-list-icon[left_list='"+element_id+"']").attr('style','display:block');
        }else{
            value.setAttribute('style','display:none;');
            if(element_id > 0)
                $(".window-left-list-icon[left_list='"+element_id+"']").attr('style','display:none');
        }
        return value;
    });
}

function showModalLoading(){
    $('.window-action-panel').addClass('window-hidden');
    $('#modal-loading-panel').removeClass('window-hidden');
}

function hideModalLoading(){
    $('.window-action-panel').addClass('window-hidden');
    $('#windowModal').modal('hide');
}

/*-------------------------------------------
 *      AJAX CALLS
 -------------------------------------------*/

function ajaxCreateLeftList(pathname, value,token) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: pathname,
        data: {type : 'create-left-list', value : value, _token : token},
        beforeSend: function (xhr) {
            
        }
    })
            .fail(function (data){
                showToast("Error","Something went wrong and the action was not saved",-1);
                //$('#create-left-list').prop('disabled', false);
                $('#create-left-list').button('reset');
    })
            .done(function (data) {
                if(data['error'] == 1){
                    showToast("Error",data['error_msg'],-1);
                }else{
                    showToast("Success",data['error_msg'],3);
                    $('ul#window-left-list').append("<img class='window-left-list-icon' left_list='"+data['id']+"' src='/img/details_close.png'><li class='window-left-list-element window-left-sublist-shown' left_list='"+data['id']+"' left_value='"+data['name']+"'><span class='left-list-name'>"+data['name']+"</span><span class='edit-buttons'></span><ul class='window-left-sublist' left_list='"+data['id']+"'></ul></li>");
                    droppableFunction();
                }
                //$('#create-left-list').prop('disabled', false);
                $('#create-left-list').button('reset');
                showEditButtons();
            });
}

function ajaxDropElements(pathname,left_id,dragged_id,token){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: pathname,
        data: {type : 'dragged-item', left_id : left_id, dragged_id: dragged_id, _token : token},
        beforeSend: function (xhr) {
            $(".window-left-sublist[left_list='"+left_id+"'").append("<img class='drop-loading' src='/img/loading/loading1.gif'>");
        }
    })
            .fail(function (data){
                $(".window-left-sublist[left_list='"+left_id+"'").children('.drop-loading').remove();
                showToast("Error","Something went wrong and the action was not saved",-1);
    })
            .done(function (data) {
                $(".window-left-sublist[left_list='"+left_id+"'").children('.drop-loading').remove();
                if(data['error'] === 1){
                    showToast("Error",data['error_msg'],-1);
                }else{
                    if(data['error'] === 2){
                        showToast("Warning",data['error_msg'],2);
                    }else{
                        showToast("Success",data['error_msg'],3); 
                    }

                    var right_name2 = Array();
                    var right_id = jQuery.parseJSON(data['right_id']);
                    var right_name = jQuery.parseJSON(data['right_name']);
                    if(data['right_name2'] != 'undefined'){
                        right_name2 = jQuery.parseJSON(data['right_name2']);
                    }

                    if(data['right_id'] !== "" ){
                        $.each(right_id,function(index,value){
                            $(".window-left-sublist[left_list='"+data['left_id']+"'").append("<li class='window-left-list-subelement' left_list='"+value+"'><input type='checkbox' parent_id='"+data['left_id']+"' child_id='"+value+"'> "+right_name[index]+"  <span style='float:right;align:right'>"+right_name2[index]+"</span></li>");
                        });
                        showEditButtons();
                    }
                }
            });
}

function ajaxEditAction(pathname,action,left_id,left_id_sub,value,token){
    $.ajax({
        type: "POST",
        dataType: "json",
        url: pathname,
        data: {type : action, left_id : left_id, left_id_sub : left_id_sub, value: value, _token : token},
        beforeSend: function (xhr) {
            showModalLoading();
        }
    })
    .fail(function (data){
        hideModalLoading();
        showToast("Error","Something went wrong and the action was not saved",-1);
    })
    .done(function (data) {
        hideModalLoading();
        if(data['error'] === 0){
            if(action === 'edit-left-list'){
                $(".window-left-list-element[left_list='"+data['left_id']+"']").children('.left-list-name').html(data['left_name']);
            }else if(action === 'copy-left-list'){
                var sublist_content = $(".window-left-sublist[left_list='"+left_id+"']").html();
                $('ul#window-left-list').append("<img class='window-left-list-icon' left_list='"+data['left_id']+"' src='/img/details_close.png'><li class='window-left-list-element' left_list='"+data['left_id']+"' left_value='"+data['left_name']+"'><span class='left-list-name'>"+data['left_name']+"</span><span class='edit-buttons'></span><ul class='window-left-sublist' left_list='"+data['left_id']+"'>"+sublist_content+"</ul></li>");
                droppableFunction();
            }else if(action === 'delete-left-list'){
                console.log('deleted');
                $(".window-left-list-element[left_list='"+data['left_id']+"']").remove();
                $(".window-left-list-icon[left_list='"+data['left_id']+"']").remove();
            }else if(action === 'delete-left-list-element'){
                $.each(left_id_sub.split(','),function(index,value){
                    $(".window-left-list-subelement[left_list='"+value+"']").remove();
                });
                console.log('deleted');
            }
            showToast("Success",data['error_msg'],3);
        }else{
            showToast("Error",data['error_msg'],-1);
        }

    });
}


