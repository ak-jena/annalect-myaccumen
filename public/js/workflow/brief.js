/**
 * Created by saeed.bhuta on 08/12/2016.
 */

$(document).ready(function() {

    // forms
    var campaign_info_1_form = $('form#campaign-info-form-1');
    var campaign_info_2_form = $('form#campaign-info-form-2');
    var display_media_mobile_1_form = $('form#display-media-mobile-1');
    var display_media_mobile_2_form = $('form#display-media-mobile-2');
    var display_media_mobile_3_form = $('form#display-media-mobile-3');

    var audio_1_form = $('form#audio-1');
    var audio_2_form = $('form#audio-2');
    var video_1_form = $('form#video-1');
    var video_2_form = $('form#video-2');

    var file_upload_form = $('form#file-upload');
    var submit_brief_form = $('form#submit-brief');

    // form divs
    var display_media_mobile_div_1 = $('div#display-media-mobile-1-slide');
    var display_media_mobile_div_2 = $('div#display-media-mobile-2-slide');
    var display_media_mobile_div_3 = $('div#display-media-mobile-3-slide');
    var audio_div_1 = $('div#audio-1');
    var audio_div_2 = $('div#audio-2');
    var video_div_1 = $('div#video-1');
    var video_div_2 = $('div#video-2');

    // fields
    var products_checkbox_elem = $('form#campaign-info-form-1 input[type=checkbox]');

    // fields used to hold campaign_id, once a campaign and brief have been created (from first form)
    var campaign_id = $('input#campaign_id');

    // duplicate brief
    var duplicate_brief_select = $('select#existing_campaign');

    // to determine if a new or existing brief is being created/edited
    var operation_type = $('input#operation_type');

    var audio_budget_input = $('input#audio_budget');
    var display_budget_input = $('input#display_budget');
    var rich_media_budget_input = $('input#rich_media_budget');
    var mobile_budget_input = $('input#mobile_budget');
    var vod_budget_input = $('input#vod_budget');

    var audio_help_button = $('button#audio_help');
    var display_help_button = $('button#display_help');
    var rich_media_help_button = $('button#rich_media_help');
    var mobile_help_button = $('button#mobile_help');
    var vod_help_button = $('button#vod_help');

    var dsp_tube_mogul_input = $('input#planned_vod_dsp_budget_tube_mogul');
    var dsp_aol_input = $('input#planned_vod_dsp_budget_aol');
    var dsp_dbm_input = $('input#planned_vod_dsp_budget_dbm_budget');
    var dsp_amazon_input = $('input#planned_vod_dsp_budget_amazon');
    var dsp_tradedesk_input = $('input#planned_vod_dsp_budget_the_tradedesk');
    var dsp_videology_input = $('input#planned_vod_dsp_budget_videology');
    var dsp_brightroll_input = $('input#planned_vod_dsp_budget_brightroll');

    var total_budget_input = $('input#total_budget');

    // vod dsp related elements
    var vod_dsp_div = $('div#vod_dsps');
    var vod_unsure_checkbox_elem = $('form#campaign-info-form-2 :checkbox');

    // record which products were selected
    var products_ids = $('input#products_ids');

    // record which products were selected for display, media and mobile form
    var rmd_products_ids = [];

    // client side validation for file upload (problems with Laravel's built in file upload file validation rules)
    // Taken from http://stackoverflow.com/questions/40529003/laravel-file-upload-validation-doesnt-trigger-tokenmismatchexception
    $('input[type="file"]', file_upload_form).change(function () {
        if (this.files[0] != undefined) {
            // console.log(getFileExtension(this.files[0].name));
            var id = this.id;
            console.log(id);
            var name = this.name;
            var filesize = this.files[0].size;
            var show_message = false;
            var error_message = '';

            var file_extension = getFileExtension(this.files[0].name);

            // console.log(file_extension);
            // var field = $('#' + this.name).parents('.input-group');

            //check if file size is larger than 3MB(which is 3e+6 bytes)
            if (filesize > 5000000) {
                // alert(filesize);

                error_message = 'File is too big.';
                show_message = true;

                //reset that input field if its file size is more than 5MB
                $('#'+id).fileinput('clear');

            }

            // check file extension
            if (file_extension != 'csv' && file_extension != 'xls' && file_extension != 'xlsx' && file_extension != 'pdf' && file_extension != 'docx' && file_extension != 'doc' ) {
                show_message = true;
                error_message += ' Invalid file type.';

                $('#'+id).fileinput('clear');
            }

            if(show_message){
                sweetAlert('Invalid file', error_message, 'error');
            }else{
                $('button#file-upload-submit').prop('disabled', false);
            }
        }
    });

    $('[data-toggle="popover"]').popover();

    $('input#campaign_dates').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });


    $('input#campaign_dates').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
    });

    $('input#campaign_dates').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    // set default to 5 days in advance
    if(!brief_response_deadline){
        // console.log('is null');
        brief_response_deadline = new Date();
        var days_ahead = 5;
        brief_response_deadline.setDate(brief_response_deadline.getDate() + days_ahead);
        // console.log(brief_response_deadline.toDateString());
    }/*else{
        // console.log('there is a date');
    }*/

    $('input#deadline').daterangepicker({
        locale: {
            format: 'DD-MM-YYYY'
        },
        singleDatePicker: true,
        startDate: brief_response_deadline
    });

    // set operation type (existing or new)
    if(url_campaign_id == null){
        operation_type.val('new');
    }else{
        operation_type.val('existing');
        campaign_id.val(url_campaign_id);

        //
    }

    $( ".next-button" ).click(function(e) {
        goRight();
    });
    $( ".back-button" ).click(function(e) {
        goLeft();
    });

    // for sliding functionality
    function goRight(){ // inner stuff slides left
        var initalLeftMargin = $( ".inner-container" ).css('margin-left').replace("px", "")*1;
        var newLeftMargin = (initalLeftMargin - 1110); // extra 2 for border
        $( ".inner-container" ).animate({marginLeft: newLeftMargin}, 500);
    }
    function goLeft(){ // inner stuff slides right
        var initalLeftMargin = $( ".inner-container" ).css('margin-left').replace("px", "")*1;
        var newLeftMargin = (initalLeftMargin + 1110); // extra 2 for border
        $( ".inner-container" ).animate({marginLeft: newLeftMargin}, 500);
    }

    // for adding/removing budget fields
    function toggleBudgetFields(product_ids){
        // hide all budget fields
        $('.budget-fields').hide();
        $('#vod-dsp-budget').hide();
        $('#vod-dsp-unsure').hide();

        // disable all budget fields (this needs to be done to
        // prevent them being submitted in the form submission)
        $('.budget-fields :input').prop('disabled', true);
        toggleVodDspFields();

        // show relevant budget fields
        $.each(product_ids, function(i, val){

            // show relevant field
            if(val == '4'){
                $('#audio_budget').show();
                audio_budget_input.prop('disabled', false);
                audio_help_button.prop('disabled', false);
            }
            if(val == '1'){
                $('#display_budget').show();
                display_budget_input.prop('disabled', false);
                display_help_button.prop('disabled', false);
            }
            if(val == '2'){
                $('#rich_media_budget').show();
                rich_media_budget_input.prop('disabled', false);
                rich_media_help_button.prop('disabled', false);
            }
            if(val == '3'){
                $('#mobile_budget').show();
                mobile_budget_input.prop('disabled', false);
                mobile_help_button.prop('disabled', false);
            }
            if(val == '5'){
                $('#vod_budget').show();
                $('#vod-dsp-budget').show();
                $('#vod-dsp-unsure').show();

                vod_budget_input.prop('disabled', false);
                vod_help_button.prop('disabled', false);
                $('#vod-dsp-budget :input').prop('disabled', false);
            }

            // console.log('val: '+product_ids);
        });

    }

    // for adding/removing forms
    function toggleBriefForms(product_ids) {
        // hide all forms
        display_media_mobile_div_1.hide();
        display_media_mobile_div_2.hide();
        display_media_mobile_div_3.hide();
        audio_div_1.hide();
        audio_div_2.hide();
        video_div_1.hide();
        video_div_2.hide();

        // show relevant budget fields
        $.each(product_ids, function(i, val){
            if(val == '4'){
                audio_div_1.show();
                audio_div_2.show();
            }
            if(val == '1' || val == '2' || val == '3'){
                display_media_mobile_div_1.show();
                display_media_mobile_div_2.show();
                display_media_mobile_div_3.show();
            }
            if(val == '5'){
                video_div_1.show();
                video_div_2.show();
            }
        });
    }

    // attach form submit handler for campaign_info_1_form
    $(campaign_info_1_form).submit(function(){
        // console.log('form submitted');

        // submit button animation
        var l = Ladda.create($('#campaign-info-1-submit')[0]);
        l.start();

        var serialized_form_data = campaign_info_1_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_campaign_info_1_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                console.log(data);

                // save the newly created campaign id in the next forms hidden input
                campaign_id.val(data.campaign_id);


                // set the op type to existing to prevent creating another
                // campaign if user decides to make a change without refreshing the page
                operation_type.val('existing');

                // take user to next form
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $("div[class*='field']", form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // add error message
                    // $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for campaign_info_2_form
    $(campaign_info_2_form).submit(function(){
        // console.log('campaign form 2 submitted');

        // submit button animation
        var l = Ladda.create($('#campaign-info-2-submit')[0]);
        l.start();

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_campaign_info_2_url,
            data: campaign_info_2_form.serialize(),
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    // console.log('div#' + index);

                    // replace . with _
                    var formatted_index = index.replace('.','_');

                    form_group_div = $('div#' + formatted_index);
                    console.log(form_group_div);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $("div[class*='field']", form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for display_media_mobile_1_form
    $(display_media_mobile_1_form).submit(function(){
        // console.log('display, media, mobile form 1 submitted');

        // submit button animation
        var l = Ladda.create($('#display-media-mobile-1-submit')[0]);
        l.start();

        var serialized_form_data = display_media_mobile_1_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });
        serialized_form_data.push({name: 'products_ids', value: rmd_products_ids });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_display_media_mobile_1_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for display_media_mobile_2_form
    $(display_media_mobile_2_form).submit(function(){
        // console.log('display, media, mobile form 2 submitted');
        // submit button animation
        var l = Ladda.create($('#display-media-mobile-2-submit')[0]);
        l.start();

        var serialized_form_data = display_media_mobile_2_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });
        serialized_form_data.push({name: 'products_ids', value: rmd_products_ids });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_display_media_mobile_2_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for display_media_mobile_3_form
    $(display_media_mobile_3_form).submit(function(){
        // console.log('display, media, mobile form 3 submitted');
        // submit button animation
        var l = Ladda.create($('#display-media-mobile-3-submit')[0]);
        l.start();

        var serialized_form_data = display_media_mobile_3_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });
        serialized_form_data.push({name: 'products_ids', value: rmd_products_ids });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_display_media_mobile_3_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for audio_1 form
    $(audio_1_form).submit(function(){
        // console.log('audio 1 form submitted');
        // submit button animation

        // submit button animation
        var l = Ladda.create($('#audio-1-submit')[0]);
        l.start();

        // add campaign id to form
        var serialized_form_data = audio_1_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_audio_1_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for audio_2 form
    $(audio_2_form).submit(function(){
        // console.log('audio 2 form submitted');

        // add campaign id to form
        var serialized_form_data = audio_2_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });

        // submit button animation
        var l = Ladda.create($('#audio-2-submit')[0]);
        l.start();

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_audio_2_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for video-1 form
    $(video_1_form).submit(function(){
        // console.log('video 1 form submitted');

        // submit button animation
        var l = Ladda.create($('#video-1-submit')[0]);
        l.start();

        // add campaign id to form
        var serialized_form_data = video_1_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_video_1_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for video-2 form
    $(video_2_form).submit(function(){
        // console.log('video 2 form submitted');

        // submit button animation
        var l = Ladda.create($('#video-2-submit')[0]);
        l.start();

        // add campaign id to form
        var serialized_form_data = video_2_form.serializeArray();
        serialized_form_data.push({name: 'campaign_id', value: campaign_id.val() });

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_video_2_url,
            data: serialized_form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
            }
        }).always(function() {
            // console.log('always fired!');
            l.stop();
        });
        // prevent form from being submitted
        return false;
    });

    // attach form submit handler for file-upload form
    $(file_upload_form).submit(function(){
        // console.log('file upload form submitted');

        // submit button animation
        var l = Ladda.create($('#file-upload-submit')[0]);
        l.start();

        var form_data = new FormData($(this)[0]);

        // add campaign id to form
        form_data.append('campaign_id', campaign_id.val() );

        // console.log(form_data);

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_file_upload_url,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                // take user to next form if successful
                goRight();
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
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

    // attach form submit handler for submit brief form
    $(submit_brief_form).submit(function(){
        // console.log('brief form submitted');

        // submit button animation
        var l = Ladda.create($('#brief-submit')[0]);
        l.start();

        var form_data = new FormData($(this)[0]);

        // add campaign id to form
        form_data.append('campaign_id', campaign_id.val() );

        // console.log(form_data);

        // clear previous errors
        $('.help-block').remove();

        $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: brief_submit_url,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);

                // sweetAlert("Brief submitted successfully!");

                swal({
                    title: 'Success',
                    type: 'success',
                    text: 'Brief submitted successfully',
                    timer: 3000
                });

                // redirect user to dashboard after 4 seconds
                // similar behavior as clicking on a link
                window.setTimeout(function() {
                    window.location.href = dashboard_url;
                }, 3000);
            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);

                // show error messages
                $.each(errors, function(index, value) {
                    form_group_div = $('div#' + index);

                    $(form_group_div).addClass('has-error');

                    // add error message
                    $(form_group_div).append('<span class=\"help-block\">' + value + '</span>');

                    // console.log('index: '+ index);
                    // console.log('value: '+ value);
                });
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

    // if duplicate brief select exists attach handler to
    // refresh the page (with pre-populated fields) whenever
    // brief is selected
    if(duplicate_brief_select.length){
        duplicate_brief_select.change(function(){
            var duplicate_brief_id = $(this).val();
            console.log($(this).val());
            window.location = '/workflow/0/'+duplicate_brief_id;
        });
    }


    // product checkbox change handler
    // add/remove budget fields depending on products selected
    // add/remove forms depending on products selected
    products_checkbox_elem.change(function() {

        // reset budget fields to '0'
        audio_budget_input.val(0.00);
        display_budget_input.val(0.00);
        rich_media_budget_input.val(0.00);
        mobile_budget_input.val(0.00);
        vod_budget_input.val(0.00);

        total_budget_input.val(0.00);

        resetVodDspBudgets();

        rmd_products_ids = [];
        // console.log('Selected product(s): '+$(this).val());

        // all checked products
        var checkedValues = $('input[name="product[]"]:checked').map(function() {
            return this.value;
        }).get();

        var display_media_mobile_ids = [];

        // add display media mobile ids
        $.each(checkedValues, function(i, val){
            // console.log(val);
            if(val == 1 || val == 2 || val == 3){
                display_media_mobile_ids.push(val);
                rmd_products_ids.push(val);
            }
        });

        // rmd_products_ids.val(display_media_mobile_ids);
        // console.log(rmd_products_ids);

        toggleBudgetFields(checkedValues);
        toggleBriefForms(checkedValues);
        toggleVodDspFields();

    });

    // attach event to budget fields to calculate and update total budget value
    $("input[id*='budget']").focusout(function() {
        updateBudgetTotal();
    });

    // attach event to vod dsp budget fields to calculate and update total vod budget value
    $("input[id^='planned_vod_dsp_budget_']", campaign_info_2_form).focusout(function() {
        console.log('dsp focussed out');

        // convert input to values to int
        var tube_mogul_budget = parseFloat(dsp_tube_mogul_input.val()) ||  0;
        var aol_budget = parseFloat(dsp_aol_input.val()) ||  0;
        var dbm_budget = parseFloat(dsp_dbm_input.val()) ||  0;
        var amazon_budget = parseFloat(dsp_amazon_input.val()) ||  0;
        var tradedesk_budget = parseFloat(dsp_tradedesk_input.val()) ||  0;
        var videology_budget = parseFloat(dsp_videology_input.val()) ||  0;
        var brightroll_budget = parseFloat(dsp_brightroll_input.val()) ||  0;

        var total_vod_budget = tube_mogul_budget + aol_budget + dbm_budget + amazon_budget + tradedesk_budget + videology_budget + brightroll_budget;
        total_vod_budget = total_vod_budget*1;

        vod_budget_input.val(total_vod_budget.toFixed(2));

        updateBudgetTotal();
        // console.log(total_vod_budget_int);

    });

    // vod dsp unsure checkbox handler
    // if checked, disable budget input for dsps
    vod_unsure_checkbox_elem.change(function() {
        toggleVodDspFields();
    });

    function updateBudgetTotal() {
        // convert input to values to int
        var audio_budget = parseFloat(audio_budget_input.val()) ||  0;
        var display_budget = parseFloat(display_budget_input.val()) || 0;
        var rich_media_budget = parseFloat(rich_media_budget_input.val()) || 0;
        var mobile_budget = parseFloat(mobile_budget_input.val()) || 0;
        var vod_budget = parseFloat(vod_budget_input.val()) || 0;

        var total_budget = audio_budget + display_budget + rich_media_budget + mobile_budget + vod_budget;
        total_budget = total_budget*1;
        var total_budget_int = total_budget_input.val(total_budget.toFixed(2));
    }

    function resetVodDspBudgets() {
        dsp_tube_mogul_input.val(0.00);
        dsp_aol_input.val(0.00);
        dsp_dbm_input.val(0.00);
        dsp_amazon_input.val(0.00);
        dsp_tradedesk_input.val(0.00);
        dsp_videology_input.val(0.00);
        dsp_brightroll_input.val(0.00);
    }

    function toggleVodDspFields() {
        // console.log('fired toggleVodDspFields');
        if(vod_unsure_checkbox_elem.is(':checked')){
            // console.log('vod_unsure_checkbox_elem is checked');
            // disable budget fields
            $('#vod-dsp-budget input').prop('disabled', true);
            // reset vod dsp budgets
            resetVodDspBudgets();
        }else{
            // console.log('not checked');
            $('#vod-dsp-budget :input').prop('disabled', false);
        }
    }

    // highlight the selected section of accordion
    $('#workflow-accordion').on('show.bs.collapse', function (e) {

        // get the opened panel
        var panel = $('#'+e.target.id);

        // make heading active (its a sibling element to the selected panel)
        var panelHeadingDiv = panel.prev();
        panelHeadingDiv.addClass('active-title');

        // replace down chevron with up chevron
        panelHeadingDiv.find('div.accuen-down').replaceWith('<div class="text-center accuen-up"><span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></div>');

        // scroll page to opened panels content
        $('html, body').animate({
            scrollTop: panelHeadingDiv.offset().top/* + -30*/
        }, 500);
    });

    // deselect the closed section of accordion
    $('#workflow-accordion').on('hidden.bs.collapse', function (e) {
        // get the opened panel
        var panel = $('#'+e.target.id);

        // make heading inactive (its a sibling element to the selected panel)
        var panelHeadingDiv = panel.prev();
        panelHeadingDiv.removeClass('active-title');

        // replace down chevron with up chevron
        panelHeadingDiv.find('div.accuen-up').replaceWith('<div class="text-center accuen-down"><span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span></div>');
    });

    // hide all budget fields by default
    $('.budget-fields').hide();

    // disable all budget fields (this needs to be done to
    // prevent them being submitted in the form submission)
    $('.budget-fields :input').prop('disabled', true);

    // default forms
    var checkedProducts = $('input[name="product[]"]:checked').map(function() {
        return this.value;
    }).get();
    toggleBriefForms(checkedProducts);

    toggleBudgetFields(checkedProducts);

    toggleVodDspFields();

    $.each(checkedProducts, function(i, val){
        // console.log(val);
        if(val == 1 || val == 2 || val == 3){
            rmd_products_ids.push(val);
        }
    });

    // file edit handler
    $('a.edit-brief-file').click(function (e) {
        // console.log('edit brief file clicked!');

        // get the id
        var brief_file_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        console.log(brief_file_id);

        $('div#existing-brief-file-'+brief_file_id).hide('slow');
        $('div#upload-brief-file-'+brief_file_id).show('slow');

        // Cancel the default action
        e.preventDefault();

    });

    //
    $('a.cancel-brief-file').click(function (e) {
        // console.log('edit brief file clicked!');

        // get the id
        var brief_file_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        // console.log(brief_file_id);

        $('div#existing-brief-file-'+brief_file_id).show('slow');
        $('div#upload-brief-file-'+brief_file_id).hide('slow');

        // Cancel the default action
        e.preventDefault();

    });

    // disable 'audience reach' option in primary campaign metric select
    $('#primary_campaign_metric option[value="Audience Reach"]').attr('disabled', true);

});
