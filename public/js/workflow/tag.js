/**
 * Created by saeed.bhuta on 14/03/2017.
 */

$(document).ready(function() {
    // forms
    var tag_form = $('form#tags_upload_form');
    var declaration_checkbox_elem = $('input#declaration');
    var fileshare_links_input_elem = $('textarea#fileshare_links');
    var files_ok = false;

    // client side validation for file upload (problems with Laravel's built in file upload file validation rules)
    // Taken from http://stackoverflow.com/questions/40529003/laravel-file-upload-validation-doesnt-trigger-tokenmismatchexception
    $('input[type="file"]', tag_form).change(function () {
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
                files_ok = false;
            }else{
                files_ok = true;
            }
        }

        // uncheck declaration box
        declaration_checkbox_elem.prop( "checked", false ).change();
    });

    fileshare_links_input_elem.focusout(function() {
        // uncheck declaration box
        declaration_checkbox_elem.prop( "checked", false ).change();
    });

    declaration_checkbox_elem.change(function() {
        if(this.checked){
            console.log('checkbox is checked!');
            if(files_ok){
                $('button#tag-submit').prop('disabled', false);
            }
        }else{
            console.log('checkbox isnt checked!');
            $('button#tag-submit').prop('disabled', true);
        }
    });


    // register which button was clicked
    $('button[type=submit]').click(function (e) {
        var button = $(this);
        button_form = button.closest('form');
        button_form.data('submission_type', button);
    });

    // tag form submission handler
    $(tag_form).submit(function(){
        console.log('tag form submitted');

        // submit button animation
        var l = Ladda.create(document.activeElement); // 'document.activeElement gets whatever button was clicked

        l.start();
        var form = $(this);
        var submission_type = form.data('submission_type');
        // console.log(submission_type.val());

        var form_data = new FormData($(this)[0]); // has to be formData object to handle file upload
        form_data.append('submission_type',  submission_type.val());

        // Display the key/value pairs
        // for (var pair of form_data.entries()) {
        //     console.log(pair[1].size);
        //     console.log(pair[0]+ ', ' + pair[1]);
        // }

        // clear previous errors
        // $('.help-block').remove();

        // $('.has-error').removeClass('has-error');

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_tags_url,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                console.log(data);
                sweetAlert('Success', 'Creative tags submitted successfully!', 'success');
                swal({
                    title: 'Success',
                    type: 'success',
                    text: data.message,
                    timer: 3000
                });


                // redirect user to dashboard
                // similar behavior as clicking on a link
                window.setTimeout(function() {
                    window.location.href = dashboard_url;
                }, 3000);

            },
            error: function(data){
                // validation failed
                var errors = $.parseJSON(data.responseText);
                console.log(errors);

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
});

function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}