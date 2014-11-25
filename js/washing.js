/**
 *
 * Created by jjallen on 11/20/14.
 */

var washer_ids = ["#washer-1","#washer-2","#washer-3","#washer-4"];
var washers = [];
var NUM_WASHERS = 4;
var selected_washer_index = -1;
var url_base = "php";

$(document).ready(function() {
    /*
        Initialize washers
     */
    updateAvailableColors();

    for (var i = 0; i < NUM_WASHERS; i++) {
        var washer;
        // GET each washer json
        $.ajax(url_base + "/machine.php/washer/" + (i+1) + "/",
            {
                type: "GET",
                async: false,
                dataType: "json",
                success: function (washer_json, status, jqXHR) {
                    washer = new Machine(washer_json);
                },
                error: function (jqXHR, status, error) {
                    // This (hopefully means) that there hasn't been any action associated
                    console.log("washer " + (i+1) + " faliure:" + jqXHR.responseText);
                }
            });

        washers.push(washer);
        // These attributes allow the link to present a popup
        $(washer_ids[i]).attr("data-rel","popup");
        $(washer_ids[i]).attr("data-position-to","window");
        $(washer_ids[i]).attr("data-transition","pop");

        // This handler keeps track of which washer was clicked last
        // used for determining who called the load/unload
        $(washer_ids[i]).on('click', function (e) {
            selected_washer_index = $(this).attr('id').slice(-1) - 1;
        });

        $(washer_ids[i]).append(washer.makeCompactDiv());
        if (washer.isLoad){ // Last action was a load, expecting an unload
            $(washer_ids[i]).prop("href","#unload_wash_popup");
        } else {
            $(washer_ids[i]).prop("href","#load_wash_popup");
        }
    }

    // Load washer
    $("#load-washer-submit-btn").click(function(e){
        e.preventDefault(); // Don't submit form
        var colorID = $("#select-color-wload").val();
        var loadedWasher = washers[selected_washer_index];
        loadedWasher.isDryer = 0;
        loadedWasher.isLoad = 1;
        loadedWasher.num = selected_washer_index + 1;
        loadedWasher.eid = sessionStorage.eid; // Assuming html5 sessionStorage compatibility screened already
        loadedWasher.lid = lidForColorID(colorID);

        // Post load action to DB
        var washer = postWasherAction(loadedWasher);
        if (washer != null){
            washers[selected_washer_index] = washer;
            $(washer_ids[selected_washer_index]).empty();
            $(washer_ids[selected_washer_index]).append(washer.makeCompactDiv());

            // If the object is waiting to unload
            if (washer.isLoad){
                $(washer_ids[i]).prop("href","#unload_wash_popup");
            } else {
                $(washer_ids[i]).prop("href","#load_wash_popup");
            }
        }

        $('#load_wash_popup').popup('close');
    });

    // Unload washer
    $("#unload-washer-submit-btn").click(function(e){
        e.preventDefault();

        var unloadedWasher = washers[selected_washer_index];
        unloadedWasher.isDryer = 0;
        unloadedWasher.isLoad = 0;
        unloadedWasher.num = selected_washer_index + 1;
        unloadedWasher.eid = sessionStorage.eid; // Assuming html5 sessionStorage compatibility screened already
        // lid remains the same

        // Post load action to DB
        var washer = postWasherAction(unloadedWasher);
        if (washer != null){
            washers[selected_washer_index] = washer;
            $(washer_ids[selected_washer_index]).empty();
            $(washer_ids[selected_washer_index]).append(washer.makeCompactDiv());

            // If the object is waiting to unload
            if (washer.isLoad){
                $(washer_ids[i]).prop("href","#unload_wash_popup");
            } else {
                $(washer_ids[i]).prop("href","#load_wash_popup");
            }
        }
        $('#unload_wash_popup').popup('close');
    });

});

/*
    Fills load dialog with assigned colors
 */
function updateAvailableColors(){
    $("#select-color-wload").empty();
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
                        $("#select-color-wload").append(option);
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
                    $("#select-color-wload").append(option);
                    $('option[value='+(-1)+']').attr('selected', 'selected');
                }
                // Redraw the select element
                $("#select-color-wload").selectmenu('refresh');
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

function postWasherAction(machine){
    $.ajax(url_base + "/machine.php/washer/",
        {
            type: "POST",
            async: false,
            data: machine,
            success: function(washer_json, status, jqXHR) {
                console.log("Washer action completed.");
                return new Machine(washer_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure posting washer action : " + jqXHR.responseText);
                return null;
            }});
}

