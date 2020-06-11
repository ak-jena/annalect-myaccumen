/**
 * Created by saeed.bhuta on 02/06/2017.
 */

var filter_form_elem = $('form#dashboard_filter_form');
var client_select_elem = $('select#client_id');
var product_select_elem = $('select#product_id');
var date_range_input_elem = $('input#date_range_filter');

var tiles_div_elem = $('div#campaign-tiles');

$(document).ready(function() {
    console.log('welcome');

    date_range_input_elem.daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'DD-MM-YYYY'
        },
        opens: 'left'
    });

    date_range_input_elem.on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
        loadTiles();
    });

    date_range_input_elem.on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        loadTiles();
    });


    function loadTiles(){
        // get data
        $.ajax({
            url: load_live_campaigns_tiles_url,
            type: 'get',
            data: {
                client_id : client_select_elem.val(),
                product_id : product_select_elem.val(),
                date_range: date_range_input_elem.val()
            },
            success: function(response) {
                //
                console.log('success');
                tiles_div_elem.empty();
                tiles_div_elem.append(response);

                $('[data-toggle="popover"]').popover();
            },
            error: function (xhr) {
                console.log('error');
            }
        });
    }

    function sortTiles(column_id, sort_order){
        var column = $('div#'+column_id);
        // console.log(column.length);

        // var campaigns = new_briefs_board.children('.panel').detach().get();
        var campaigns = column.children();
        // console.log(campaigns.length);

        campaigns.sort(function (a, b) {
            if(sort_order == 'asc'){
                return new Date($(a).data("date")) - new Date($(b).data("date"));
            }else if(sort_order == 'des'){
                return new Date($(b).data("date")) - new Date($(a).data("date"));
            }
        });

        column.append(campaigns);
    }

    // load tiles on page load
    loadTiles();

    // handler for sort
    $('.sort').click(function (e) {
        var id_value = this.id;

        var underscore_pos = id_value.indexOf('_');

        var column_id = id_value.substr(0, underscore_pos);
        // console.log(column_id);
        var sort_order = id_value.substr(id_value.length - 3);
        // console.log(sort_order);

        sortTiles(column_id, sort_order);

        // Cancel the default action
        e.preventDefault();
    });

    // handler for filter button
    $('#filter').click(function (e) {
        var id_value = this.id;
        console.log(id_value);

        $('#date-filter').toggle('slow');
        $('#client-filter').toggle('slow');
        // date_range_input_elem.toggle('slow');
        // $('.glyphicon-calendar').toggle('slow');
        // client_select_elem.toggle('slow');
        // product_select_elem.toggle('slow');

        // reset filters
        date_range_input_elem.val('');

        client_select_elem.val('');
        product_select_elem.val('');

        loadTiles();

        // Cancel the default action
        e.preventDefault();
    });

    // handler for client select filter
    $(client_select_elem).change(function() {
        // console.log('Client changed!');
        loadTiles();
    });

    // handler for client select filter
    $(product_select_elem).change(function() {
        // console.log('Product changed!');
        loadTiles();
    });

});