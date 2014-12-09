/**
 *
 * Created by jjallen on 11/20/14.
 */

var dryer_ids = ["#dryer-1","#dryer-2","#dryer-3","#dryer-4","#dryer-5","#dryer-6"];
var dryers = [];
var NUM_DRYERS = 6;
var current_dryer = -1;
var selected_dryer_index = -1;

var url_base = "php";

$(document).on('pagecreate',function() {
    /*
     Initialize dryers
     */
    refreshDryers();

    // Load dryer
    $("#load-dryer-submit-btn").click(function(e){
        e.preventDefault(); // Don't submit form
        var colorID = $("#select-color-dload").val();
        var loadedWasher = dryers[selected_dryer_index];
        loadedWasher.isDryer = 1;
        loadedWasher.isLoad = 1;
        loadedWasher.num = selected_dryer_index + 1;
        loadedWasher.eid = sessionStorage.eid; // Assuming html5 sessionStorage compatibility screened already
        loadedWasher.lid = lidForColorID(colorID);

        // Post load action to DB
        var dryer = postDryerAction(loadedWasher);
        if (dryer != null){
            refreshDryers();
        } else {
            console.log("TODO: Load dryer error handling.")
        }

        $('#load_dry_popup').popup('close');
    });

    // Unload dryer
    $("#unload-dryer-submit-btn").click(function(e){
        e.preventDefault();

        var unloadedDryer = dryers[selected_dryer_index];
        unloadedDryer.isDryer = 1;
        unloadedDryer.isLoad = 0;
        unloadedDryer.num = selected_dryer_index + 1;
        unloadedDryer.eid = sessionStorage.eid; // Assuming html5 sessionStorage compatibility screened already
        // lid remains the same

        // Post load action to DB
        var dryer = postDryerAction(unloadedDryer);
        if (dryer != null){
            refreshDryers();
        } else {
            console.log("TODO: Unload dryer error handling.")
        }
        $('#unload_dry_popup').popup('close');
    });

    $("#refresh-dry-btn").click(function(e){
        e.preventDefault();
        refreshDryers();
    });
});

function refreshDryers(){
    updateAvailableColors();
    for (var i = 0; i < NUM_DRYERS; i++) {
        var dryer;
        $.ajax(url_base + "/machine.php/dryer/" + (i+1),
            {
                type: "GET",
                async: false,
                dataType: "json",
                success: function (dryer_json, status, jqXHR) {
                    dryer = new Machine(dryer_json);
                },
                error: function (jqXHR, status, error) {
                    // This (hopefully means) that there hasn't been any action associated
                    console.log("Failure getting dryer info:" + jqXHR.responseText);
                }
            });

        dryers.push(dryer);
        // These attributes allow the link to present a popup
        $(dryer_ids[i]).empty();
        $(dryer_ids[i]).attr("data-rel","popup");
        $(dryer_ids[i]).attr("data-position-to","window");
        $(dryer_ids[i]).attr("data-transition","pop");

        // This handler keeps track of which washer was clicked last
        // used for determining who called the load/unload
        $(dryer_ids[i]).on('click', function (e) {
            selected_dryer_index = $(this).attr('id').slice(-1) - 1;
        });

        $(dryer_ids[i]).append(dryer.makeCompactDiv());
        if (dryer.isLoad){ // Last action was a load, expecting an unload
            $(dryer_ids[i]).prop("href","#unload_dry_popup");
        } else {
            $(dryer_ids[i]).prop("href","#load_dry_popup");
        }
    }
}
/*
 Fills load dialog with assigned colors
 */
function updateAvailableColors(){
    $("#select-color-dload").empty();
    $.ajax(url_base + "/color.php/",
        {
            type: "GET",
            async: false,
            success: function(color_json, status, jqXHR) {
                var colors = color_json['colors'];
                var lids = color_json['lids'];
                var first = -1;

                // Obtain all assigned colors
                for (var i=0; i < colors.length; i++){
                    // Assigned Colors
                    if (lids[i] > 0){
                        var option = '<option value="'+(i+1)+'">'+colors[i]+'</option>';
                        $("#select-color-dload").append(option);
                        if (first == -1){
                            first = i+1;
                        }
                    }
                }

                // Select first option by default
                if (first != -1){
                    $('option[value='+first+']').attr('selected', 'selected');
                } else {
                    var option = '<option value="'+(-1)+'">No available options</option>';
                    $("#select-color-dload").append(option);
                    $('option[value='+(-1)+']').attr('selected', 'selected');
                }
                // Redraw the select element
                $("#select-color-dload").selectmenu('refresh');
                return;
            },
            error: function(jqXHR, status, error) {
                console.log("failure fetching colors:"+jqXHR.responseText);
                return;
            }});
}

function lidForColorID(colorID){
    var tmp = -1;
    $.ajax(url_base + "/color.php/" + colorID,
        {
            type: "GET",
            async: false,
            success: function(color_json, status, jqXHR) {
                tmp = color_json['lid'];
            },
            error: function(jqXHR, status, error) {
                console.log('color.php/<colorID> failed :' +jqXHR.responseText);
            }});
    return tmp;
}

function postDryerAction(machine){
    var dryer = null;
    $.ajax(url_base + "/machine.php/",
        {
            type: "POST",
            async: false,
            data: machine,
            success: function(dryer_json, status, jqXHR) {
                console.log("Dryer action completed.");
                dryer = new Machine(dryer_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure posting dryer action : " + jqXHR.responseText);
                return null;
            }});
    return dryer;
}

