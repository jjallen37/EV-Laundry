/**
 * sorting.js
 */

var url_base = "php";
$(document).ready(function() {

    var colorID = -1;
    var cid = -1;
    var eid = -1;

    /*
     Populate color/customer select menus
     An available color is one without an associated lid,
     as in it is not currently assigned to another laundry.
     */
    loadColorMenu();
    loadCustomerMenu();

    /*
        Transition to sorting screen is done in html
     */
    //$("#start_sorting").click(function(e) {

    /*
        When the user submits the counts
     */
    $('#sorting-submit-btn').click(function(e) {
        // Verify Job coach questions
        var inventory_accurate = $('#inventory_accurate_sort').prop('checked');
        var no_items_on_floor = $('#items_on_floor').prop('checked');
        if (!inventory_accurate) {
            alert("Error: The clothing counts must be accurate before submission.");
            return;
        }
        if (!no_items_on_floor) {
            alert("Error: All clothing must be off the floor before submission.");
            return;
        }

        // Create Laundry
        var cid = $('#select-customer-name').val();
        var color_id = $('#select-color-sort').val();
        var laundry = createLaundry(cid, color_id);
        if (laundry === null){
            alert("Error: Laundry could not be created");
            window.location.href = "sorting.html";
            return;
        }
        console.log("Laundry created");

        // Create Sort
        var tops = $("#slider-tops-sort").val();
        var bottoms = $("#slider-bottoms-sort").val();
        var socks = $("#slider-socks-sort").val();
        var other = $("#slider-towels-sort").val();
        var count = createCount(tops, bottoms, socks, other, 0, 0);
        if (count === null){
            alert("Error: Sort counts could not be created");
            window.location.href = "sorting.html";
        }
        console.log("Sort count created");

        // Create Event linking laundry with sort data
        var lid = laundry.lid;
        var eid = sessionStorage.eid;
        var id = count.count_id;
        var event_action = Event.ActionEnum.SORT;
        var timestamp = new Date().getTime();
        var event = createEvent(lid, eid, id, event_action, timestamp);
        if (event === null){
            alert("Error: Sort event could not be created.");
            window.location.href = "sorting.html";
        }
        console.log("Sort event created");
        window.location.href = "sorting.html";
    });
});

function loadColorMenu(){
    // Fill color chooser with available colors
    var select_color = $("#select-color-sort");
    $.ajax(url_base + "/color.php/free/",
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
                    $('#start_sorting').addClass('ui-disabled');
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

function loadCustomerMenu(){
    // Fill customer chooser with available customers
    var select_customer = $("#select-customer-name");
    $.ajax(url_base + "/customers.php/free/",
        {
            type: "GET",
            async: false,
            success: function(cids, status, jqXHR) {
                // No available customers
                if (cids.length == 0){
                    var option = $('<option value="'+(-1)+'">No available customers.</option>');
                    option.attr('selected', 'selected');
                    select_customer.append(option);
                    select_customer.selectmenu('refresh');
                    $('#start_sorting').addClass('ui-disabled');
                } else {
                    // Create select option for each color
                    $.each(cids, function (i, cid){
                        var customer = findCustomerByID(cid);
                        select_customer.append(customer.makeSelectOption());
                        select_customer.selectmenu('refresh');
                    });
                }
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching available customers:"+jqXHR.responseText);
            }});
}


