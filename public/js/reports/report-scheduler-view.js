/**
 * Created by norman.tong on 23/06/2016.
 */

$(document).ready(function(){
    $("input[name='end_date']").datepicker({
        placeholder : 'Date format (yyyy-mm-dd)',
        format: 'yyyy-mm-dd'
    });

    $(".row-report-delete").hover(
        function(){
            $(this).find('.row-report-id').addClass('hidden');
            $(this).find('.row-report-bin').removeClass('hidden');
        },function(){
            $(this).find('.row-report-bin').addClass('hidden');
            $(this).find('.row-report-id').removeClass('hidden');
        });

    $(".row-report-bin").click(function(){
        var report_id = $(this).parent('td').data('reportid');
        $("input[name='delete-report-id']").val(report_id);
        $("#modal-delete-confirmation").modal();
    });


    /* Edit functionality */
    $(".editable").dblclick(function(){
        var scheduler_id = $(this).closest('tr').data('reportid');
        $("input[name='selected-scheduler-edit-id']").val(scheduler_id);

        var target  = $(this).data('target');
        var content = $(this).text();
        showEditForm(target,content);
    });

    $(".edit-confirm").click(function(){
        var data = {};
        var input_type = $(this).closest('.form-group').find('.form-control').attr('name');
        var input_value = $(".form-control[name='"+input_type+"']").val();
        data[input_type] = input_value;
        ajaxEditParameter(data);
    });

    $(".edit-cancel").click(function(){
        $(".edit-group").each(function(){
            $(this).addClass('hidden');
        });
    });

    $("select[name='frequency']").change(function(){
        var frequency = $(this).val();
        showFrequencyMenu(frequency);
    });
});

function showEditForm(target,content){
    $(".edit-group").each(function(){
        $(this).addClass('hidden');
    });
    console.log(target);
    $(".edit-group[target='"+target+"']").removeClass('hidden');

    switch(target){
        case 'name' :
            var input_value = content;
            break;
        case 'email' :
            var input_value = content.split(' ').join(',');
            break;
        case 'end_date' :
            var input_value = content;
            break;
        default:
            break;
    }

    if(input_value){
        $(".edit-group[target='"+target+"']").find('input').val(input_value);
    }

    if(target == 'frequency'){
        var frequency = $("select[name='frequency']").val();
        showFrequencyMenu(frequency);
    }
}

function showFrequencyMenu(frequency){
    switch(frequency){
        case 'd' :
            $(".edit-group[target='day_week']").addClass('hidden');
            $(".edit-group[target='day_month']").addClass('hidden');
            break;
        case 'w' :
            $(".edit-group[target='day_week']").removeClass('hidden');
            $(".edit-group[target='day_month']").addClass('hidden');
            break;
        case 'm' :
            $(".edit-group[target='day_week']").addClass('hidden');
            $(".edit-group[target='day_month']").removeClass('hidden');
            break;
    }
}

function ajaxEditParameter(data){
    var token           = $("input[name='_token']").val();
    var scheduler_id    = $("input[name='selected-scheduler-edit-id']").val();

    /* Special cases, when more data should be sent - Frequency and Data Length */
    $.each(data,function(key,value){
        if(key == 'frequency'){
            switch(value){
                case 'w' :
                    data['day_week'] = Array();
                    $("input[name='day_week[]']:checked").each(function(){
                        data['day_week'].push($(this).val());
                    });
                    break;
                case 'm' :
                    data['day_month'] = $("select[name='day_month']").val();
                    break;
            }
        }

    });

    var data_json = JSON.stringify(data);

    $.ajax({
        type : 		"POST",
        dataType : 	"json",
        url : 		"/reports/report-scheduler/edit-scheduled-report",
        data : 		{data : data_json, scheduler_id : scheduler_id,  _token : token},
        beforeSend: function( xhr ) {
            $(".edit-confirm-buttons").addClass('hidden');
            $(".edit-loading").removeClass('hidden').html("<img src='/img/loading/loading1.gif'>");
        }
    })
        .done(function( data ) {
            $(".edit-loading").addClass('hidden').html("");
            $(".edit-confirm-buttons").removeClass('hidden');
            $(".edit-group").each(function(){
                $(this).addClass('hidden');
            });

            console.log(data);
            for(var data_type in data){
                console.log(data_type);
                if(data_type == 'frequency'){
                    $("td[data-target='day_week']").html('-');
                    $("td[data-target='day_month").html('-');
                }
                $("td[data-target='"+data_type+"']").html(data[data_type]);
            }

        }).error(function(data){
        $("#ajax-error-alert").html('There was a server error when making the petition');
        $("#ajax-error-alert").show().delay(5000).fadeOut();

        $(".edit-loading").addClass('hidden').html("");
        $(".edit-confirm-buttons").removeClass('hidden');
    });
}