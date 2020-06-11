//Create targeting type
var targetingTypeEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select><option value='Audience '>Audience </option><option value='Environment'>Environment</option></select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create dsp
var dspEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='Adswizz '>Adswizz </option> <option value='Viedology '>Viedology </option> <option value='DBM '>DBM </option> <option value='AppNexus '>AppNexus </option> <option value='Tradedesk '>Tradedesk </option> <option value='StrikeAd'>StrikeAd</option> <option value='Adelphic'>Adelphic</option> <option value='Brightroll '>Brightroll </option> <option value='Amazon'>Amazon</option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create goal
var goalEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='VTR '>VTR </option> <option value='CTR '>CTR </option> <option value='Audience Reach '>Audience Reach </option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create screens
var screensEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='Desktop '>Desktop </option> <option value='Tablet'>Tablet</option> <option value='Mobile '>Mobile </option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create format
var formatEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='MPU '>MPU </option> <option value='Billboard'>Billboard</option> <option value='Double MPU '>Double MPU </option> <option value='Leaderboard '>Leaderboard </option> <option value='Skins'>Skins</option> <option value='Banner'>Banner</option> <option value='Standard Formats'>Standard Formats</option> <option value='Sublime Skinz - Classic Skinz'>Sublime Skinz - Classic Skinz</option> <option value='Sublime Skinz - Wingz'>Sublime Skinz - Wingz</option> <option value='Sublime Skinz - Video Skinz'>Sublime Skinz - Video Skinz</option> <option value='Sublime Skinz - Video Skinz Billboard'>Sublime Skinz - Video Skinz Billboard</option> <option value='Sublime Skinz - Video Classic'>Sublime Skinz - Video Classic</option> <option value='Sublime Skinz - Skin Bill'>Sublime Skinz - Skin Bill</option> <option value='Sublime Skinz - Shopping Skinz'>Sublime Skinz - Shopping Skinz</option> <option value='Sublime Skinz - Billboard Skinz'>Sublime Skinz - Billboard Skinz</option> <option value='Sublime Skinz - Mobile Swipe'>Sublime Skinz - Mobile Swipe</option> <option value='Collective - RISE'>Collective - RISE</option> <option value='Collective - Brand Skins'>Collective - Brand Skins</option> <option value='Collective - Expandable (REM)'>Collective - Expandable (REM)</option> <option value='Scoota - Lightbox (Scoota) '>Scoota - Lightbox (Scoota) </option> <option value='Scoota - Page Shell'>Scoota - Page Shell</option> <option value='Scoota - Page Shift'>Scoota - Page Shift</option> <option value='Scoota - Prism'>Scoota - Prism</option> <option value='Undertone - Page Wrap'>Undertone - Page Wrap</option> <option value='Undertone - Mobile Video Teaser'>Undertone - Mobile Video Teaser</option> <option value='Undertone - Expandable Teaser'>Undertone - Expandable Teaser</option> <option value='Undertone - See through display'>Undertone - See through display</option> <option value='Undertone - See through Video'>Undertone - See through Video</option> <option value='Undertone - Screenshift'>Undertone - Screenshift</option> <option value='Undertone - Page Grabber'>Undertone - Page Grabber</option> <option value='Undertone - U Motion'>Undertone - U Motion</option> <option value='InSkin - Pageskin'>InSkin - Pageskin</option> <option value='InSkin - Pageskin Edge'>InSkin - Pageskin Edge</option> <option value='InSkin - Pageskin Plus'>InSkin - Pageskin Plus</option> <option value='InSkin - Pageskin Superwide'>InSkin - Pageskin Superwide</option> <option value='Inskin - Pageskin Superwide Evolve'>Inskin - Pageskin Superwide Evolve</option> <option value='Inskin - Page Skin Swipeout'>Inskin - Page Skin Swipeout</option> <option value='DoubleClick Studio - Lightbox (Doubleclick)'>DoubleClick Studio - Lightbox (Doubleclick)</option> <option value='Just Premium - Push Up Billboard '>Just Premium - Push Up Billboard </option> <option value='Just Premium - Lightbox'>Just Premium - Lightbox</option> <option value='Just Premium - Wallpaper'>Just Premium - Wallpaper</option> <option value='Just Premium - Video Wallpaper'>Just Premium - Video Wallpaper</option> <option value='Just Premium - Custon Video Floorad'>Just Premium - Custon Video Floorad</option> <option value='Just Premium - Mobile Interscroller'>Just Premium - Mobile Interscroller</option> <option value='Kargo - Venti'>Kargo - Venti</option> <option value='Kargo - Spotlight'>Kargo - Spotlight</option> <option value='Kargo - Key Art'>Kargo - Key Art</option> <option value='Kargo - Sidekick'>Kargo - Sidekick</option> <option value='Kargo - Breakout'>Kargo - Breakout</option> <option value='Teads '>Teads </option> <option value='Audio 30's '>Audio 30's </option> <option value='Audio 10's'>Audio 10's</option> <option value='Audio 60's'>Audio 60's</option> <option value='Audio 15's'>Audio 15's</option> <option value='Audio 20's'>Audio 20's</option> <option value='VAST '>VAST </option> <option value='V-Paid'>V-Paid</option> <option value='Instream '>Instream </option> <option value='Youtube'>Youtube</option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create inventory
var inventoryEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='OMGP'>OMGP</option> <option value='Custom Site List'>Custom Site List</option> <option value='PMP'>PMP</option> <option value='Custom Site List / PMP'>Custom Site List / PMP</option> <option value='Trueview'>Trueview</option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

//Create tech fee
var techFeeEditor = function(cell, onRendered, success, cancel){
    //cell - the cell component for the editable cell
    //onRendered - function to call when the editor has been rendered
    //success - function to call to pass the succesfully updated value to Tabulator
    //cancel - function to call to abort the edit and return to a normal cell

    //create and style editor
    var editor = $("<select> <option value='7.50%'>7.50%</option> <option value='8.50%'>8.50%</option> <option value='10%'>10%</option> </select>");
    editor.css({
        "padding":"3px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    //Set value of editor to the current value of the cell
    editor.val(cell.getValue());

    //set focus on the select box when the editor is selected (timeout allows for editor to be added to DOM)
    onRendered(function(){
        editor.focus();
        editor.css("height","100%");
    });

    //when the value has been set, trigger the cell to update
    editor.on("change blur", function(e){
        success(editor.val());
    });

    //return the editor element
    return editor;
};

var dateEditor = function (cell, onRendered, success, cancel) {
    //create and style input
    var input = $("<input type='text'/>");

    input.datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
    });

    input.css({
        "border":"1px",
        "background":"transparent",
        "padding":"4px",
        "width":"100%",
        "box-sizing":"border-box",
    });

    input.val(cell.getValue());

    onRendered(function(){
        input.focus();
    });
    var inputBlur = function(e){
        console.log(input[0]);
        console.log(e.target);
        if(e.target != input[0]){
            if( $(e.target).closest(".ui-datepicker").length == 0){
                $("body").off("mousedown", inputBlur);
                success(input.val());
            }
        }
    }

    $("body").on("mousedown", inputBlur);

    //submit new value on blur
    $("body").on("change", input, function(e){
        $("body").off("mousedown", inputBlur);
        success(input.val());
    });

    $("body").on("click", input, function(e){e.stopPropagation()});

    //submit new value on enter
    $("body").on("keydown", input, function(e){
        if(e.keyCode == 13){
            $("body").off("mousedown", inputBlur);
            success(input.val());
        }
    });

    return input;
};

var product = "rich-media";

function getNewRowId() {
    var product_tg_div_id = "#"+product+"-targeting-grid";

    var grid_rows = $(product_tg_div_id).tabulator("getData");

    var row_ids = [];

    var array_length = grid_rows.length;
    for (var i = 0; i < array_length; i++) {
        // alert(grid_rows[i]);
        //Do something
        var grid_row = grid_rows[i];
        row_ids.push(grid_row.id);
    }

    row_ids.sort(function(a, b){return a-b});

    var last_id = row_ids.slice(-1)[0];

    return last_id+=1;

}

$(document).ready(function() {
    var products = ["rich-media", "mobile", "display", "audio", "vod"];

    $.each(products, function( index, value ) {
        var product_tg_div_id = "#"+value+"-targeting-grid";

        $(product_tg_div_id).tabulator({
            ajaxURL:load_targeting_grid_data_url,

            // layout: "fitDataFill", //fit columns to width of table (optional)
            columns: [ //Define Table Columns
                {title: "Actions", field: "actions", formatter:"html"},
                {title: "DSP", field: "dsp", editor: dspEditor, validator:"required"},
                {title: "Targeting Type", field: "targeting_type", editor: targetingTypeEditor/*validator:"required"*/},
                {title: "Targeting Tactic", field: "targeting_tactic", editor: true/*validator:"required"*/},
                {title: "Goal - Campaign KPI or Activity Specific (leave blank for campaign KPI)", field: "goal", editor: goalEditor/*validator:"required"*/, width: 250},
                {title: "KPI Value", field: "kpi_value", editor: true/*validator:"required"*/},
                {title: "Role", field: "role", editor: true/*validator:"required"*/},
                {title: "Screens", field: "screens", editor: screensEditor/*validator:"required"*/},
                {title: "Format", field: "format", editor: formatEditor/*validator:"required"*/},
                {title: "Inventory", field: "inventory", editor: inventoryEditor/*validator:"required"*/},
                {title: "Estimated potential budget composition %*", field: "est_budget_percentage", editor: true/*validator:"required"*/},
                {title: "Budget (£)", field: "budget", formatter:"money"/*validator:"required"*/, cssClass:"highlight-calculated-column", align:"center"},
                {title: "Estimated average CPM* and CPV (TV)", field: "est_avg_cpm_cpv", formatter:"money", editor: true/*validator:"required"*/},
                {title: "Views (TV)", field: "views"/*validator:"required"*/, cssClass:"highlight-calculated-column", align:"center"},
                {title: "Estimated Impressions", field: "est_impressions"/*validator:"required"*/, cssClass:"highlight-calculated-column", align:"center"},
                {title: "Format details", field: "format_details", editor: true/*validator:"required"*/},
                {title: "Data fee", field: "data_fee", editor: true/*validator:"required"*/},
                {title: "Tech fee", field: "tech_fee"/*validator:"required"*/, editor: techFeeEditor},
                {title: "Start", field: "start", editor: dateEditor/*validator:"required"*/},
                {title: "End", field: "end", editor: dateEditor/*validator:"required"*/}

            ],
            cellEdited:function(cell){
                var field = cell.getField();
                var row_data = cell.getData();
                var row_id = row_data.id;

                // update relevant calculated fields
                switch(field){
                    case "est_budget_percentage":
                    case "est_avg_cpm_cpv":

                        // calulate budget
                        var brief_budget = math.number($("#budget").val());
                        var est_budget_comp = math.number(parseFloat(row_data.est_budget_percentage));
                        var budget = math.eval(brief_budget*est_budget_comp/100);

                        // calulate views
                        var est_avg_cpm_cpv = math.number(row_data.est_avg_cpm_cpv);
                        // var views = math.eval(budget/est_avg_cpm_cpv);
                        var views =  math.format(math.eval(budget/est_avg_cpm_cpv), {notation: 'fixed', precision: 2});
                        // math.format(views, {precision: 2});

                        // calulate estimated impressions
                        var estimated_impressions = math.format(math.eval((budget/est_avg_cpm_cpv)*1000), {notation: 'fixed', precision: 2});

                        // update cells for this row
                        $(product_tg_div_id).tabulator("updateData", [{
                            id:row_id, budget: budget, views: views, est_impressions: estimated_impressions
                        }]);

                        break;
                }


                // console.log(cell.getData());
            }
        });
    });
});

//Add row on "Add Row" button click
$(".add-row").click(function(){
    // identify which product's grid
    // var product = $(this).data("product");
    // console.log(product);

    var product_tg_div_id = "#"+product+"-targeting-grid";

    // increment previous row id
    var new_row_id = getNewRowId();

    // console.log(new_row_id);

    $(product_tg_div_id).tabulator("addRow", {id: new_row_id, actions: '<button type="button" data-tg-id="'+new_row_id+'" class="delete-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button>  <button type="button" data-tg-id="'+new_row_id+'" class="copy-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> </button>'});
});

// delete button handler
$("body").on("click", "button.delete-tg-row", function() {
    // console.log('delete button selected');

    var product_tg_div_id = "#"+product+"-targeting-grid";

    // get row id from custom data attribute
    var tg_row_id = $(this).data('tg-id');

    // delete the row
    $(product_tg_div_id).tabulator("deleteRow", tg_row_id);

});

// copy button handler
$("body").on("click", "button.copy-tg-row", function() {
    // console.log(getNewRowId());

    var product_tg_div_id = "#"+product+"-targeting-grid";

    // get row id from custom data attribute
    var tg_row_id = $(this).data('tg-id');

    // console.log(tg_row_id);

    // get all rows data
    var data_array = $(product_tg_div_id).tabulator("getData");

    // get the row data
    var row_data = data_array[tg_row_id-1];

    // console.log(row_data);

    // set to new id
    var new_row_id = getNewRowId();

    row_data.id = new_row_id;
    // buttons should have correct id
    row_data.actions = '<button type="button" data-tg-id="'+new_row_id+'" class="delete-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </button>  <button type="button" data-tg-id="'+new_row_id+'" class="copy-tg-row btn btn-default btn-xs"><span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span> </button>';

    // add the row
    $(product_tg_div_id).tabulator("addRow", row_data);

});

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    // e.target // newly activated tab
    // e.relatedTarget // previous active tab

    product = e.target.id;

    var product_tg_div_id = "#"+e.target.id+"-targeting-grid";

    $(product_tg_div_id).tabulator("setData");

})
