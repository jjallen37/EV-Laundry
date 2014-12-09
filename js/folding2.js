/**
 * folding.js
 */

var url_base = "php";
$(document).ready(function() {

    /*
     Populate color/customer select menus
     An available color is one without an associated lid,
     as in it is not currently assigned to another laundry.
     */
    loadColorMenu();

    /*
     Transition to folding screen is done in html
     */
    //$("#start_folding").click(function(e) {

    /*
     When the user submits the counts
     */
    $('#folding-submit-btn').click(function(e) {
        // Verify Job coach questions
        var neatly_stacked = $('#neatly_stacked').prop('checked');
        var inventory_accurate = $('#fold_inventory_accurate').prop('checked');
        if (!neatly_stacked) {
            alert("Error: All clothing must neatly stacked before submission.");
            return;
        }
        if (!inventory_accurate) {
            alert("Error: The clothing counts must be accurate before submission.");
            return;
        }

        // Create Fold
        var tops = $("#slider-tops-fold").val();
        var bottoms = $("#slider-bottoms-fold").val();
        var socks = $("#slider-socks-fold").val();
        var other = $("#slider-towels-fold").val();
        var hang_tops = $("#slider-tops-hung").val();
        var hang_bottoms = $("#slider-bottoms-hung").val();
        var count = createCount(tops, bottoms, socks, other, hang_tops, hang_bottoms);
        if (count === null){
            alert("Error: Fold counts could not be created");
            window.location.href = "folding.html";
        }
        console.log("Fold count created");

        // Create Event linking laundry with fold data
        var color_id = $("#select-color-fold").val();
        var lid = findLidFromColorID(color_id);
        if (lid < 0){
            alert("Error: Could not find laundry from color.");
            window.location.href = "folding.html";
            return;
        }
        var eid = sessionStorage.eid;
        var id = count.count_id;
        var event_action = Event.ActionEnum.FOLD;
        var timestamp = new Date().getTime();
        var event = createEvent(lid, eid, id, event_action, timestamp);
        if (event === null){
            alert("Error: Fold event could not be created.");
            window.location.href = "folding.html";
        }
        console.log("Fold event created");

        // Compare sort and fold
        var laundry = findLaundryByID(lid);
        if (laundry == null){
            alert("Error: Could not find laundry item.");
            window.location.href = "folding.html";
        }

        var sort = laundry.getSort();
        if (sort == null){
            alert("Error: Could not find sort data.");
            window.location.href = "folding.html";
            return;
        }
        var fold = laundry.getFold();
        if (fold == null){
            alert("Error: Could not find fold data.");
            window.location.href = "folding.html";
            return;
        }

        laundry.status = Laundry.LaundryStatus.DONE;
        laundry.updateStatus();
        if (!sort.equals(fold)){
            alert("Error: Fold Numbers Different than Sort.");
        }
        window.location.href = "folding.html";
    });
});

function loadColorMenu(){
    // Fill color chooser with available colors
    var select_color = $("#select-color-fold");
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
                    $('#start_folding').addClass('ui-disabled');
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

