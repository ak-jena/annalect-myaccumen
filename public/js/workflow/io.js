/**
 * Created by saeed.bhuta on 23/03/2017.
 */

$(document).ready(function() {
    // forms
    var io_form = $('form#io_form');
    // var links_submit_button = $('button#submit-links');
    // var dds_code_submit_button = $('button#submit-io');
    var io_submit_button = $('button#submit-io');

    // client side validation for file upload (problems with Laravel's built in file upload file validation rules)
    // Taken from http://stackoverflow.com/questions/40529003/laravel-file-upload-validation-doesnt-trigger-tokenmismatchexception
    $('input[type="file"]', io_form).change(function () {
        if (this.files[0] != undefined) {
            // console.log(getFileExtension(this.files[0].name));

            var name = this.name;
            var filesize = this.files[0].size;
            var show_message = false;
            var error_message = '';

            var file_extension = getFileExtension(this.files[0].name);

            // console.log(file_extension);
            // var field = $('#' + this.name).parents('.input-group');

            //check if file size is larger than 2MB
            if (filesize > 2000000) {
                // alert(filesize);

                error_message = 'File is too big.';
                show_message = true;

                //reset that input field if its file size is more than 5MB
                $('[name="' + name + '"]').val('');
            }

            if(show_message){
                sweetAlert('Invalid file', error_message, 'error');
            }else{
                $('button#grid-submit').prop('disabled', false);
            }
        }
    });

    // register which button was clicked
    $('button[type=submit]').click(function (e) {
        console.log('button clicked');
        var button = $(this);
        button_form = button.closest('form');
        button_form.data('submission_type', button);
    });

    // grid form submission handler
    $(io_form).submit(function(){
        console.log('io form submitted');

        // submit button animation
        var l = Ladda.create(document.activeElement); // 'document.activeElement gets whatever button was clicked

        l.start();

        var form = $(this);
        var submission_type = form.data('submission_type');
        // console.log(submission_type.val());

        var form_data = new FormData($(this)[0]); // has to be formData object to handle file upload
        form_data.append('submission_type',  submission_type.val());

        // var serialized_form_data = dsps_budgets_form.serializeArray();
        // serialized_form_data.push({name: 'submission_type', value: submission_type.val() });

        // clear previous errors
        // $('.help-block').remove();

        $('.form-group').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_io_url,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                // console.log(data);
                var alert_message;
                if(data.operation_type == 'Links saved.') {
                    alert_message = 'Host links saved successfully.';

                    swal({
                        title: 'Success',
                        type: 'success',
                        text: alert_message,
                        timer: 3000
                    });

                    // show submit buttons
                    if(data.section_complete == true){
                        io_submit_button.show();
                    }else{
                        // hide submit button
                        io_submit_button.hide();
                    }
                }else if(data.operation_type == 'DDS code saved.'){
                    alert_message = 'DDS codes and IO files saved successfully.';

                    swal({
                        title: 'Success',
                        type: 'success',
                        text: alert_message,
                        timer: 3000
                    });

                    if(data.section_complete == true){
                        io_submit_button.show();
                    }else{
                        // hide submit button
                        io_submit_button.hide();
                    }
                }else if(data.operation_type == 'Submitted'){
                    alert_message = 'IO submitted successfully.';

                    // sweetAlert('Success', alert_message, 'success');
                    swal({
                        title: 'Success',
                        type: 'success',
                        text: alert_message,
                        timer: 3000
                    });

                    // redirect user to dashboard after 4 seconds
                    // similar behavior as clicking on a link
                    window.setTimeout(function() {
                        window.location.href = dashboard_url;
                    }, 3000);
                }

            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                // console.log(errors);
                validation_keys = Object.keys(errors);
                console.log(validation_keys);

                for (i = 0; i < validation_keys.length; i++) {
                    // form the input id
                    var input_id = validation_keys[i].replace(/\./g, '_');

                    // console.log('input#'+input_id);
                    $('input#'+input_id).closest('div.form-group').addClass('has-error');
                }

                sweetAlert('Error', 'Please ensure all fields have been completed correctly. DDS codes should be unique.', 'error');

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

    // file edit handler
    $('a.edit-file').click(function (e) {
        // console.log('edit file clicked!');

        // get the id
        var dsp_budget_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        // console.log(dsp_budget_id);

        $('div#existing-file-'+dsp_budget_id).hide('slow');
        $('div#upload-file-'+dsp_budget_id).show('slow');

        // Cancel the default action
        e.preventDefault();

    });

    //
    $('a.cancel-file').click(function (e) {
        // console.log('edit file clicked!');

        // get the id
        var dsp_budget_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        // console.log(dsp_budget_id);

        $('div#existing-file-'+dsp_budget_id).show('slow');
        $('div#upload-file-'+dsp_budget_id).hide('slow');

        // Cancel the default action
        e.preventDefault();

    });
});

function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}