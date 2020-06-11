/**
 * Created by norman.tong on 16/06/2016.
 */

$(document).ready(function(){
    $("select[name='regions[]']").select2({
        placeholder : "Select the regions",
        allowClear 	: true,
    });

    // Delete option
    $("button[name='delete-region-before']").click(function(e){
        e.preventDefault();
        $("#form-delete-region-before").addClass('hidden');
        $("#form-delete-region-after").removeClass('hidden');
        showRegionCheckboxes();
    });
    $("button[name='cancel-region-after']").click(function(e){
        e.preventDefault();
        $("#form-delete-region-after").addClass('hidden');
        $("#form-delete-region-before").removeClass('hidden');
        hideRegionCheckboxes();
    });
});

function showRegionCheckboxes(){
    $(".table-region-id").each(function(){
        $(this).addClass('hidden');
    });
    $(".table-region-checkbox").each(function(){
        $(this).removeClass('hidden');
    });
}

function hideRegionCheckboxes(){
    $(".table-region-checkbox").each(function(){
        $(this).addClass('hidden');
    });
    $(".table-region-id").each(function(){
        $(this).removeClass('hidden');
    });
}