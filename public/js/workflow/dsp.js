/**
 * Created by saeed.bhuta on 01/03/2017.
 */

$(document).ready(function() {
    // forms
    var date_change_form = $('form#date_change_form');
    var dsps_budgets_form = $('form#dsps_budgets_form');

    // dsp fields
    var audio_adwizz_budget_input = $('input#audio_dsp_budget_adwizz');

    var display_amazon_budget_input = $('input#display_dsp_budget_amazon');
    var display_tradedesk_budget_input = $('input#display_dsp_budget_the_tradedesk');
    var display_appnexus_budget_input = $('input#display_dsp_budget_appnexus');
    var display_dbm_budget_input = $('input#display_dsp_budget_dbm');
    var display_brightroll_budget_input = $('input#display_dsp_budget_brightroll');

    var mobile_tradedesk_budget_input = $('input#mobile_dsp_budget_the_tradedesk');
    var mobile_appnexus_budget_input = $('input#mobile_dsp_budget_appnexus');
    var mobile_strikead_budget_input = $('input#mobile_dsp_budget_strikead');
    var mobile_adelphic_budget_input = $('input#mobile_dsp_budget_adelphic');
    var mobile_brightroll_budget_input = $('input#mobile_dsp_budget_brightroll');
    var mobile_dbm_budget_input = $('input#mobile_dsp_budget_dbm');

    var rich_media_dbm_budget_input = $('input#rich_media_dsp_budget_dbm');
    var rich_media_tradedesk_budget_input = $('input#rich_media_dsp_budget_the_tradedesk');
    var rich_media_appnexus_budget_input = $('input#rich_media_dsp_budget_appnexus');

    var vod_tube_mogul_budget_input = $('input#vod_dsp_budget_tube_mogul');
    var vod_aol_budget_input = $('input#vod_dsp_budget_aol');
    var vod_dbm_trueview_budget_input = $('input#vod_dsp_budget_dbm_budget');
    var vod_amazon_budget_input = $('input#vod_dsp_budget_amazon');
    var vod_the_tradedesk_budget_input = $('input#vod_dsp_budget_the_tradedesk');
    var vod_videology_budget_input = $('input#vod_dsp_budget_videology');
    var vod_brightroll_budget_input = $('input#vod_dsp_budget_brightroll');

    // product total fields
    var audio_total_input = $('input#audio_total_budget');
    var display_total_input = $('input#display_total_budget');
    var mobile_total_input = $('input#mobile_total_budget');
    var rich_media_total_input = $('input#rich_media_total_budget');
    var vod_total_input = $('input#vod_total_budget');

    // total budget (total of all product totals)
    var budget_total_input = $('input#booking_total_budget');

    var audio_total = (isNaN(audio_total_input.val()) ? parseFloat(0) : parseFloat(audio_total_input.val()));
    var display_total = (isNaN(display_total_input.val()) ? parseFloat(0) : parseFloat(display_total_input.val()));
    var mobile_total = (isNaN(mobile_total_input.val()) ? parseFloat(0) : parseFloat(mobile_total_input.val()));
    var rich_media_total = (isNaN(rich_media_total_input.val()) ? parseFloat(0) : parseFloat(rich_media_total_input.val()));
    var vod_total = (isNaN(vod_total_input.val()) ? parseFloat(0) : parseFloat(vod_total_input.val()));

    var budget_total = parseFloat(budget_total_input.val());

    var campaign_id = $('input#campaign_id').val();

    // attach event to budget fields to calculate and update total budget value
    $("input[id*='dsp_budget']", dsps_budgets_form).focusout(function() {
        console.log(audio_total);
        console.log(display_total);
        console.log(mobile_total);
        console.log(rich_media_total);
        console.log(audio_total);

        // console.log(this.id);
        var input_id = this.id;
        var product;

        if(input_id.indexOf('audio') > -1){
            product = 'audio';
        }else if(input_id.indexOf('display') > -1) {
            product = 'display';
        }else if(input_id.indexOf('mobile') > -1) {
            product = 'mobile';
        }else if(input_id.indexOf('rich_media') > -1) {
            product = 'rich_media';
        }else if(input_id.indexOf('vod') > -1) {
            product = 'vod';
        }

        switch (product) {
            case 'audio':
                // convert input to values to int
                var audio_adswhizz_budget_val = parseFloat(audio_adwizz_budget_input.val()) ||  0;

                // product total
                audio_total = audio_adswhizz_budget_val;
                audio_total = audio_total*1;

                audio_total_input.val(audio_total.toFixed(2));

                break;
            case 'display':
                // convert input to values to int
                var display_amazon_budget_val = parseFloat(display_amazon_budget_input.val().replace(/,/g,'')) || 0;
                var display_tradedesk_budget_val = parseFloat(display_tradedesk_budget_input.val().replace(/,/g,'')) || 0;
                var display_appnexus_budget_val = parseFloat(display_appnexus_budget_input.val().replace(/,/g,'')) || 0;
                var display_dbm_budget_val = parseFloat(display_dbm_budget_input.val().replace(/,/g,'')) || 0;
                var display_brightroll_budget_val = parseFloat(display_brightroll_budget_input.val().replace(/,/g,'')) || 0;

                // product total
                display_total = display_amazon_budget_val + display_tradedesk_budget_val + display_appnexus_budget_val + display_dbm_budget_val + display_brightroll_budget_val;
                display_total = display_total*1;

                display_total_input.val(display_total.toFixed(2));

                break;
            case 'mobile':
                var mobile_tradedesk_budget_val = parseFloat(mobile_tradedesk_budget_input.val().replace(/,/g,'')) || 0;
                var mobile_appnexus_budget_val = parseFloat(mobile_appnexus_budget_input.val().replace(/,/g,'')) || 0;
                var mobile_strikead_budget_val = parseFloat(mobile_strikead_budget_input.val().replace(/,/g,'')) || 0;
                var mobile_adelphic_budget_val = parseFloat(mobile_adelphic_budget_input.val().replace(/,/g,'')) || 0;
                var mobile_brightroll_budget_val = parseFloat(mobile_brightroll_budget_input.val().replace(/,/g,'')) || 0;
                var mobile_dbm_budget_val = parseFloat(mobile_dbm_budget_input.val().replace(/,/g,'')) || 0;

                // product total
                mobile_total = mobile_tradedesk_budget_val + mobile_appnexus_budget_val + mobile_strikead_budget_val + mobile_adelphic_budget_val + mobile_brightroll_budget_val + mobile_dbm_budget_val;
                mobile_total = mobile_total*1;

                mobile_total_input.val(mobile_total.toFixed(2));

                break;
            case 'rich_media':
                var rich_media_dbm_budget_val = parseFloat(rich_media_dbm_budget_input.val().replace(/,/g,'')) || 0;
                var rich_media_tradedesk_budget_val = parseFloat(rich_media_tradedesk_budget_input.val().replace(/,/g,'')) || 0;
                var rich_media_appnexus_budget_val = parseFloat(rich_media_appnexus_budget_input.val().replace(/,/g,'')) || 0;

                // rich media total
                rich_media_total = rich_media_dbm_budget_val + rich_media_tradedesk_budget_val + rich_media_appnexus_budget_val;
                rich_media_total = rich_media_total*1;

                rich_media_total_input.val(rich_media_total.toFixed(2));

                break;
            case 'vod':
                var vod_tube_mogul_budget_val = parseFloat(vod_tube_mogul_budget_input.val().replace(/,/g,'')) || 0;
                var vod_aol_budget_val = parseFloat(vod_aol_budget_input.val().replace(/,/g,'')) || 0;
                var vod_dbm_trueview_budget_val = parseFloat(vod_dbm_trueview_budget_input.val().replace(/,/g,'')) || 0;
                var vod_amazon_budget_val = parseFloat(vod_amazon_budget_input.val().replace(/,/g,'')) || 0;
                var vod_the_tradedesk_budget_val = parseFloat(vod_the_tradedesk_budget_input.val().replace(/,/g,'')) || 0;
                var vod_videology_budget_val = parseFloat(vod_videology_budget_input.val().replace(/,/g,'')) || 0;
                var vod_brightroll_budget_val = parseFloat(vod_brightroll_budget_input.val().replace(/,/g,'')) || 0;

                // vod total
                vod_total = vod_tube_mogul_budget_val +
                    vod_aol_budget_val +
                    vod_dbm_trueview_budget_val +
                    vod_amazon_budget_val +
                    vod_the_tradedesk_budget_val +
                    vod_videology_budget_val +
                    vod_brightroll_budget_val;

                vod_total = vod_total*1;

                vod_total_input.val(vod_total.toFixed(2));
        }

        // total budget
        budget_total = audio_total + display_total + mobile_total + rich_media_total + vod_total;
        budget_total = budget_total*1;
        budget_total_input.val(budget_total.toFixed(2));

        // console.log('dsp budget focussed out!');

    });

    // register which button was clicked
    $('button[type=submit]').click(function (e) {
        var button = $(this);
        button_form = button.closest('form');
        button_form.data('submission_type', button);
    });

    // dsp budget form submission handler
    // attach form submit handler for dsps_budgets_form
    $(dsps_budgets_form).submit(function(){
        // console.log('form submitted');

        $('#budget-success').hide();

        // submit button animation
        var l = Ladda.create(document.activeElement); // 'document.activeElement gets whatever button was clicked

        l.start();

        var form = $(this);
        var submission_type = form.data('submission_type');
        // console.log(submission_type.val());

        var form_data = new FormData($(this)[0]);
        form_data.append('submission_type',  submission_type.val());

        // var serialized_form_data = dsps_budgets_form.serializeArray();
        // serialized_form_data.push({name: 'submission_type', value: submission_type.val() });

        // console.log(serialized_form_data);

        // clear previous errors
        // $('.help-block').remove();

        // $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_dsps_budgets_url,
            // data: serialized_form_data,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // clear previous error messages
                $('#dsp-errors-alert').hide();

                $('#budget-success').show();

                // enable the add booking button

                // console.log(data.updated_products);

                // go through the updated products
                $.each(data.updated_products, function(index, value) {
                    console.log(value);
                    // remove the disabled attribute from the add booking button for the relevant product
                    $('a#'+value+'-booking').removeClass('disabled');

                });

                if(data.submitted_booking == 1){
                    // sweetAlert("Booking submitted successfully!");

                    swal({
                        title: 'Success',
                        type: 'success',
                        text: 'Booking submitted successfully',
                        timer: 3000
                    });

                    // redirect user to dashboard after 4 seconds
                    // similar behavior as clicking on a link
                    window.setTimeout(function() {
                        window.location.href = dashboard_url;
                    }, 3000);
                }
                // save the newly created campaign id in the next forms hidden input
                // campaign_id.val(data.campaign_id);

            },
            error: function(data){
                // validation failed
                $('#dsp-errors-alert').show();

                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // clear previous error messages
                $('#dsp-errors-alert').empty();

                // to prevent dupe messages
                var messages = [];

                // show error messages
                $.each(errors, function(index, value) {
                    var message_index = messages.indexOf(value[0]);

                    if(message_index == -1){
                        messages.push(value[0]);
                        // add error message
                        $('#dsp-errors-alert').append('<p>' + value + '</p>');
                    }

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });

                // scroll to top of the page, where errors are visible
                window.scrollTo(0,0);
            },
            contentType: false,
            processData: false
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // dsp budget form submission handler
    // attach form submit handler for date_change_form
    $(date_change_form).submit(function(){
        // console.log('form submitted');

        $('#budget-success').hide();

        // submit button animation
        var l = Ladda.create(document.activeElement); // 'document.activeElement gets whatever button was clicked

        l.start();

        var form = $(this);
        var submission_type = form.data('submission_type');
        // console.log(submission_type.val());

        var form_data = new FormData($(this)[0]);
        form_data.append('submission_type',  submission_type.val());

        // var serialized_form_data = dsps_budgets_form.serializeArray();
        // serialized_form_data.push({name: 'submission_type', value: submission_type.val() });

        // console.log(serialized_form_data);

        // clear previous errors
        // $('.help-block').remove();

        // $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_date_change_url,
            // data: serialized_form_data,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // clear previous error messages
                $('#dsp-errors-alert').hide();

                $('#date-success').show();
            },
            error: function(data){
                // validation failed
                $('#dsp-errors-alert').show();

                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // clear previous error messages
                $('#dsp-errors-alert').empty();

                // to prevent dupe messages
                var messages = [];

                // show error messages
                $.each(errors, function(index, value) {
                    var message_index = messages.indexOf(value[0]);

                    if(message_index == -1){
                        messages.push(value[0]);
                        // add error message
                        $('#dsp-errors-alert').append('<p>' + value + '</p>');
                    }

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });

                // scroll to top of the page, where errors are visible
                window.scrollTo(0,0);
            },
            contentType: false,
            processData: false
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // edit file handler
    $('a.edit-booking-file').click(function (e) {
        // console.log('edit booking-file clicked!');

        $('div#existing-booking-file').hide('slow');
        $('div#upload-booking-file').show('slow');

        // Cancel the default action
        e.preventDefault();

    });

    //
    $('a#cancel-booking-file').click(function (e) {
        // console.log('edit booking-file clicked!');

        $('div#existing-booking-file').show('slow');
        $('div#upload-booking-file').hide('slow');

        // Cancel the default action
        e.preventDefault();
    });

    $('input#edit_campaign_dates').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        }
    });

    // handle discard product
    // attach click handler to buttons with id begining with 'discard'
    $(".discard").click(function() {
        console.log(campaign_id);
        var product_name = $(this).val();

        var campaign_data = {
            'campaign_id' : campaign_id,
            'product_name' : product_name
        }

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d9534f',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            closeOnConfirm: false
        },
        function(){
            console.log(campaign_id);
            console.log(product_name);

            // console.log(campaign_data);
            $.ajax({
                contentType: "application/json; charset=utf-8",
                type: 'post',
                url: delete_product_url,
                data: JSON.stringify(campaign_data),
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                success: function (data) {

                    console.log(campaign_data);
                    // console.log('confirmed');
                    swal("Deleted!", "The product has been deleted.", "success");

                    // refresh
                    location.reload(true);
                },
                error: function(data){

                },

                processData: false
            })

        });



    });


});
