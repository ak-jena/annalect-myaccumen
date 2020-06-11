/**
 * Created by norman.tong on 21/06/2016.
 */

var dsps = Array();
var dsps_text = Array();

$(document).ready(function(){
    $("select[name='dsp[]']").select2({
        'placeholder' : "Select a DSP",
        'allowclear' : true,
    });
    $("input[name='end-date']").datepicker({
        placeholder : 'Date format (yyyy-mm-dd)',
        format: 'yyyy-mm-dd'
    });

    $("button[name='view-reports']").click(function(e){
        e.preventDefault();
        var action = $(this).data('action');
        window.location = "/reports/report-scheduler/view-scheduled-reports";
    });

    /* Choose the DSPs used for reporting */
    // $("button[name='select-dsp']").click(function(e){
    //     e.preventDefault();
    //     dsps = Array();
    //     dsps_text = Array();
    //     $("select[name='dsp[]']").children(':selected').each(function(){
    //         dsps.push($(this).val());
    //         dsps_text.push($(this).text());
    //     });
    //     console.log(dsps);
    //     selectDSP(dsps,dsps_text);
    // });

    $("select[name='dsp[]']").children(':selected').each(function(){
        dsps.push($(this).val());
        dsps_text.push($(this).text());
    });

    /* Show and hide filter box */
    $("input[name='filters[]']").change(function(){
        var filter_type = $(this).val();
        var filter_text = $(this).data('label');
        console.log(filter_type);
        if($(this).is(':checked')){
            showFilteringInput(dsps,dsps_text,filter_type,filter_text);
        }else{
            hideFilteringInput(filter_type);
        }
    });

    /* Show/hide frequency filters */
    $("select[name='frequency']").change(function(){
        var frequency = $(this).val();
        switch(frequency){
            case 'd' :
                $("#form-group-day-week").addClass('hidden');
                $("#form-group-day-month").addClass('hidden');
                break;
            case 'w' :
                $("#form-group-day-week").removeClass('hidden');
                $("#form-group-day-month").addClass('hidden');
                break;
            case 'm' :
                $("#form-group-day-week").addClass('hidden');
                $("#form-group-day-month").removeClass('hidden');
                break;
        }
    });

});

function selectDSP(dsps,dsps_text){
    $("select[name='dsp[]']").select2('destroy').addClass('hidden');
    $("button[name='select-dsp']").hide();
    $("#span-selected-dsp").text(dsps_text.join(', '));
    $("input[name='dsp-selected']").val(dsps.join(','));

    $("#panel-parameters").removeClass('hidden');
    $("#panel-schedule").removeClass('hidden');
}

function showFilteringInput(dsps,dsps_text,filter_type,filter_text){
    var selector_panel =  $("#panel-filtering");
    selector_panel.removeClass('hidden');
    selector_panel.find('.panel-body').append("<div class='form-group' data-filter='"+filter_type+"'></div>");
    $.each(dsps,function(index,value){
        $("#panel-filtering").find("div[data-filter='"+filter_type+"']").append("<label class='col-lg-3'>"+filter_text+" - "+dsps_text[index]+"</label>" +
            "<div class='col-lg-9'>" +
            "<input type='text' name='filter-value["+value+"]["+filter_type+"]' class='form-control'>" +
            "</div>");
    });
}

function hideFilteringInput(filter_type){
    $("#panel-filtering").find("div[data-filter='"+filter_type+"']").empty();
    $("#panel-filtering").find("div[data-filter='"+filter_type+"']").remove();
}