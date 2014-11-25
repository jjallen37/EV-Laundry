/**
 * sorting.js
 */

$(document).ready(function() {

    var time_started;
    var time_finished;
    var lid;
    var colorID;
    var eid = 2; //TEMP
    var url_base = "php";

    /*
        Load Customer and Color Data
     */

    $.ajax(url_base + "/color.php/",
        {
            type: "GET",
            async: false,
            success: function(color_json, status, jqXHR) {
                var colors = color_json['colors'];
                var lids = color_json['lids'];
                var first = -1;

                // Fill color chooser with available colors
                for (var i=0; i < colors.length; i++){
                    // Unassigned color
                    if (lids[i] <= 0){
                        var option = '<option value="'+(i+1)+'">'+colors[i]+'</option>';
                        $("#select-color-sort").append(option);
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
                    $("#select-color-sort").append(option);
                    $('option[value='+(-1)+']').attr('selected', 'selected');
                }
                // Redraw the select element
                $("#select-color-sort").selectmenu('refresh');
                return;
            },
            error: function(jqXHR, status, error) {
                console.log("failure:"+jqXHR.responseText);
                return;
            }});


    // Handle form submit
    $("input[type=submit]").click(function(e) {
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
        $.ajax(url_base + "/clothes.php/",
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
        e.preventDefault();
    });

    /*
        Create Laundry and assign color
     */
    $("#start_sorting").click(function(e) {
        $("#header-sort").text("Sorting - ");
        time_started = Date.now();

        colorID = $('#select-color-sort').val();
        $("#slider-id-num").val();
        var json_str = '{ ' +
                        '"color" : ' + $('#select-color-sort').val() + ',' +
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

});

