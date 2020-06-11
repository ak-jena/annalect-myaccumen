/**
 * Created by saeed.bhuta on 14/03/2017.
 */

$(document).ready(function() {

    // forms
    // var dsps_budgets_form = $('form#dsps_budgets_form');

    // budget fields
    var budget_0_input = $('input#silo_budget_0');
    var budget_1_input = $('input#silo_budget_1');
    var budget_2_input = $('input#silo_budget_2');
    var budget_3_input = $('input#silo_budget_3');

    // total budget (total of all product totals)
    var budget_silos_total_input = $('input#budget_silos_total');

    var budget_silos_total = parseFloat(budget_silos_total_input.val());

    var rm_creative_format_select = $('select#rm_creative_format_select');
    var rm_creative_format_input = $('input#rm_creative_format_input');

    // var rm_creative_format_hidden_input = $('input#rm_creative_format');

    var adserver_select = $('select#adserver_select');
    var adserver_input = $('input#adserver_input');

    var adserver_hidden_input = $('input#adserver');

    // console.log('fire!');

    // attach event to budget fields to calculate and update total budget value
    $("input[id^=silo_budget]").focusout(function() {
        // console.log(audio_total);
        // console.log(display_total);
        // console.log(mobile_total);
        // console.log(rich_media_total);
        // console.log(audio_total);

        console.log(this.id);
        var input_id = this.id;

        var budget_0_val = parseFloat(budget_0_input.val().replace(/,/g,'')) || 0;
        var budget_1_val = parseFloat(budget_1_input.val().replace(/,/g,'')) || 0;
        var budget_2_val = parseFloat(budget_2_input.val().replace(/,/g,'')) || 0;
        var budget_3_val = parseFloat(budget_3_input.val().replace(/,/g,'')) || 0;

        // budget_silos_total
        budget_silos_total = budget_0_val +
            budget_1_val +
            budget_2_val +
            budget_3_val;

        budget_silos_total = budget_silos_total*1;
        console.log(budget_silos_total);

        budget_silos_total_input.val(budget_silos_total.toFixed(2));

        console.log('silo budget focussed out!');

    });

    // when creative format select is changed
    // if other is selected, then show other field
    // assign value to hidden field
    $("input[type='checkbox'][name='rm_creative_format[]']").change(function () {
        console.log('rm_creative_format_checkbox changed!');

        console.log($(this).val());

        var selected_cf = [];
        selected_cf = $('.rm_cf_checkbox:checkbox:checked').map(function () {
            return this.value;
        }).get();

        console.log(selected_cf);
        if($.inArray('Other', selected_cf) > -1){
            $('div#rm_creative_format_div').show('slow');
        }else{
            $('div#rm_creative_format_div').hide('slow');
        }
    });

    adserver_select.change(function () {
        console.log('adserver_select changed!');

        if($(this).val() == 'Other'){
            // show inputr for other
            $('div#adserver_div').show('slow');
        }else{
            // populate other with value from select
            adserver_hidden_input.val(this.value);
            $('div#adserver_div').hide('slow');
        }

    });

    adserver_input.focusout(function() {
        console.log(this.value);
        adserver_hidden_input.val(this.value);
    });
});
