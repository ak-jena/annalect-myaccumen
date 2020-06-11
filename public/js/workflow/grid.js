/**
 * Created by saeed.bhuta on 14/03/2017.
 */

$(document).ready(function() {
    // forms
    var grid_form = $('form#grid_upload_form');

    // client side validation for file upload (problems with Laravel's built in file upload file validation rules)
    // Taken from http://stackoverflow.com/questions/40529003/laravel-file-upload-validation-doesnt-trigger-tokenmismatchexception
    $('input[type="file"]', grid_form).change(function () {
        if (this.files[0] != undefined) {
            // console.log(getFileExtension(this.files[0].name));

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
                $('[name="' + name + '"]').val('');
            }

            // check file extension
            if (file_extension != 'csv' && file_extension != 'xls' && file_extension != 'xlsx' ) {
                show_message = true;
                error_message += ' Invalid file type.';

                $('[name="' + name + '"]').val('');

            }

            if(show_message){
                sweetAlert('Invalid file', error_message, 'error');
            }else{
                $('button#grid-submit').prop('disabled', false);
            }
        }
    });


    // grid form submission handler
    $(grid_form).submit(function(){
        console.log('grid form submitted');

        // submit button animation
        var l = Ladda.create(document.activeElement); // 'document.activeElement gets whatever button was clicked

        l.start();

        var form_data = new FormData($(this)[0]); // has to be formData object to handle file upload

        // process form via ajax
        $.ajax({
            type: 'post',
            url: process_grid_url,
            data: form_data,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (data) {
                console.log(data);
                // clear previous error messages
                $('#grid-errors-alert').hide();

                if(data.is_successful == 1){
                    swal({
                        title: 'Success',
                        type: 'success',
                        text: 'Grid submitted successfully',
                        timer: 3000
                    });

                    // redirect user to dashboard after 4 seconds
                    // similar behavior as clicking on a link
                    window.setTimeout(function() {
                        window.location.href = dashboard_url;
                    }, 3000);
                }else{
                    // validation failed
                    $('#grid-errors-alert').show();

                    // clear previous error messages
                    $('#grid-errors-alert').empty();

                    $('#grid-errors-alert').append('<p>' + data.message + '</p>');
                }

            },
            error: function(data){
                // validation failed
                // console.log(data);
                // console.log(data.message);



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
    $('a.edit-grid').click(function (e) {
        // console.log('edit grid clicked!');

        // get the id
        var grid_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        console.log(grid_id);

        $('div#existing-grid-'+grid_id).hide('slow');
        $('div#upload-grid-'+grid_id).show('slow');

        // Cancel the default action
        e.preventDefault();

    });

    //
    $('a.cancel-grid').click(function (e) {
        // console.log('edit file clicked!');

        // get the id
        var grid_id =  this.id.replace( /^\D+/g, ''); // replace all leading non-digits with nothing
        // console.log(dsp_budget_id);

        $('div#existing-grid-'+grid_id).show('slow');
        $('div#upload-grid-'+grid_id).hide('slow');

        // Cancel the default action
        e.preventDefault();

    });

});

function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}