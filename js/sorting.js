/**
 * sorting.js
 */

$(document).ready(function() {

    var time_started;
    var time_finished;
    var lid = -1;
    var colorID = -1;
    var eid = -1; //TEMP
    var url_base = "php";
    if(typeof(Storage) !== "undefined") {
        if (sessionStorage.eid) {
            eid = sessionStorage.eid;
        }
    }

    /*
        Fetch available colors and customers
        An available color is one without an associated lid,
        as in it is not currently assigned to another laundry.
     */
    var active_lids = [];
    $.ajax(url_base + "/color.php/",
        {
            type: "GET",
            async: false,
            success: function(color_json, status, jqXHR) {
                var colors = color_json['colors'];
                var lids = color_json['lids'];
                var first = -1;

                // Fill color chooser with available colors
                var select_color = $("#select-color-sort");
                for (var i=0; i < colors.length; i++){
                    // Unassigned color
                    if (lids[i] <= 0){
                        var option = '<option value="'+(i+1)+'">'+colors[i]+'</option>';
                        select_color.append(option);
                        if (first == -1){
                            first = i+1;
                        }
                    } else {
                        active_lids.push(lids[i]);
                    }
                }

                // Select first option by default
                if (first != -1){
                    $('option[value='+first+']').attr('selected', 'selected');
                } else {
                    var option = '<option value="'+(-1)+'">No available options</option>';
                    select_color.append(option);
                    $('option[value='+(-1)+']').attr('selected', 'selected');
                    $('#start_sorting').addClass('ui-disabled');
                }
                // Redraw the select element
                select_color.selectmenu('refresh');
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching available colors:"+jqXHR.responseText);
            }});

    // Populate Customer list
    var tmp_json = '{'+
            '"hasLaundry" : 1}';
    var get_customer_json = JSON.parse(tmp_json);
    var select_customer = $("#select-customer-name");
    $.ajax(url_base + "/customers.php/",
        {
            type: "GET",
            async: false,
            data: tmp_json,
            success: function(cids, status, jqXHR) {
                $.each(cids, function (i, cid){
                    addCustomerDiv(cid, active_lids);
                });
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching customer ids:"+jqXHR.responseText);
            }});

    function addCustomerDiv(cid, active_lids) {
        // Returns
        $.ajax(url_base + "/customers.php/" + cid,
            {
                type: "GET",
                success: function (customer_json, status, jqXHR) {
                    var customer = new Customer(customer_json);
                    var option = '<option value="' + customer.cid + '">' + customer.name + '</option>';
                    select_customer.append(option);
                    select_customer.selectmenu('refresh');
                },
                error: function (jqXHR, status, error) {
                    console.log("Error fetching customer :" + cid + "..." + jqXHR.responseText);
                }
            });
    }

    /*
         Present clothe counting screen
         Save customer and color for submission
     */
    $("#start_sorting").click(function(e) {
        $("#header-sort").text("Sorting - ");
        time_started = Date.now();

        colorID = $('#select-color-sort').val();
        var json_str = '{ ' +
            '"color" : ' + colorID + ',' +
            '"cid" : 1 }';
        console.log(json_str);
        var obj = JSON.parse(json_str);

        // Insert request
        $.ajax(url_base + "/laundry.php/",
            {type: "POST",
                async: false,
                dataType: "json",
                data: obj,
                success: function(laundry_json, status, jqXHR) {
                    lid = laundry_json['lid'];
                    $("#header-sort").text("Sorting - "+laundry_json['lid']);
                },
                error: function(jqXHR, status, error) {
                    console.log("failure but why:"+jqXHR.responseText);
                    console.log(error);
                }});
    });

    /*
        Employee submitted clothing counts
        Create laundry object, add sort count data

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


        time_finished = Date.now();
        // Collect form data
        var name = $("#select-customer-name option:selected").text();
        var num_tops = $("#slider-tops-sort").val();
        var num_bottoms = $("#slider-bottoms-sort").val();
        var num_socks = $("#slider-socks-sort").val();
        var num_towels = $("#slider-towels-sort").val();


        var json_str = '{ ' +
        '"colorID":'+ colorID + ',' +
        '"isFold":'+ 0 + ',' +
        '"eid":'+eid + ',' +
        '"tops":'+num_tops + ',' +
        '"bottoms" :'+num_bottoms + ',' +
        '"socks":'+num_socks + ',' +
        '"other":'+num_towels + "}";
        console.log(json_str);

        var obj = JSON.parse(json_str);

        console.log("Clothes request");
        $.ajax(url_base + "/counts.php/",
            {type: "POST",
                async: false,
                dataType: "json",
                data: obj,
                success: function(clothes_json, status, jqXHR) {
                    window.location.href = "sorting.html";
                },
                error: function(jqXHR, status, error) {
                    console.log("failure:"+jqXHR.responseText);
                    console.log("status:"+status);
                    console.log("error:"+error);
                }});
    });

});

