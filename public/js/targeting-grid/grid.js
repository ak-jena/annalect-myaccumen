function setUpGrid() {

    // get brief data (product names + grid)
    $.get(retrieve_grid_url, function (data){

        // loop through product names and create tabs
        for(i = 0; i < data.length; i++) {
            console.log(data[i]);
        }

    }, "json");
}

$(document).ready(function() {
   setUpGrid();
});