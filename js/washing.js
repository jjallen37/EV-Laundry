/**
 *
 * Created by jjallen on 11/20/14.
 */

var washer_ids = ["#washer-1","#washer-2","#washer-3","#washer-4"];
var washers = [];
var NUM_WASHERS = 4;
var selected_washer_index = -1;
var url_base = "php";

$(document).on('pagecreate',function() {
    /*
        Initialize washers
     */
    refreshWashers();
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
            refreshWashers();
        } else {
            console.log("TODO: Handle load washer error")
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
            refreshWashers();
        } else {
            console.log("TODO: Handle unload washer error")
        }
        $('#unload_wash_popup').popup('close');
    });

    $("#refresh-wash-btn").click(function(e){
        e.preventDefault();
        refreshWashers();
    });
});

function refreshWashers(){
    updateAvailableColors();
    for (var i = 0; i < NUM_WASHERS; i++) {
        var washer;
        // GET each washer json
        $.ajax(url_base + "/events.php/washer/" + (i+1) + "/",
            {
                type: "GET",
                async: false,
                dataType: "json",
                success: function (washerID, status, jqXHR) {
                    if (washerID < 1){
                        var wash = new Event(null);
                        wash.event_action = Event.ActionEnum.UNLOAD_WASH;
                        washer = wash;
                    } else {
                        washer = findEventByID(washerID);
                    }
                },
                error: function (jqXHR, status, error) {
                    // This (hopefully means) that there hasn't been any action associated
                    console.log("washer " + (i+1) + " faliure:" + jqXHR.responseText);
                }
            });
        washers.push(washer);
        // These attributes allow the link to present a popup
        $(washer_ids[i]).empty();
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
}
/*
    Fills load dialog with assigned colors
 */
function updateAvailableColors(){
    var select_color = $("#select-color-wload");
    select_color.empty();
    $.ajax(url_base + "/color.php/active/",
        {
            type: "GET",
            async: false,
            success: function(color_ids, status, jqXHR) {
                // No available colors
                if (color_ids.length == 0){
                    var option = $('<option value="'+(-1)+'">No available colors.</option>');
                    option.attr('selected', 'selected');
                    select_color.append(option);
                    select_color.selectmenu('refresh');
                    $('#load-washer-submit-btn').addClass('ui-disabled');
                } else {
                    // Create select option for each color
                    $.each(color_ids, function (i, color_id) {
                        var color = findColorByID(color_id);
                        select_color.append(color.makeSelectOption());
                        select_color.selectmenu('refresh');
                    });
                }
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching available colors:"+jqXHR.responseText);
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
    var washer = null;
    $.ajax(url_base + "/events.php/washer/",
        {
            type: "POST",
            async: false,
            data: machine,
            success: function(washer_json, status, jqXHR) {
                console.log("Washer action completed.");
                washer = new Machine(washer_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure posting washer action : " + jqXHR.responseText);
            }});
    return washer;
}
