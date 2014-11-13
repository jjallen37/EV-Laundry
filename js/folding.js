/**
 * sorting.js
 */

$(document).ready(function() {

    var time_started;
    var time_finished;
    var url_base = "php";
    var lid = 0;
    var eid = 1;

    /*
     Load Color Data
     */
    console.log("load colors");
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
                    if (lids[i] > 0){
                        var option = '<option value="'+(i+1)+'">'+colors[i]+'</option>';
                        $("#select-color-fold").append(option);
                        if (first == -1){
                            first = i+1;
                        }
                    }
                }
                // Select first option by default
                if (first != -1){
                    $('option[value='+first+']').attr('selected', 'selected');
                } else {
                    var option = '<option value="'+(-1)+'">No Available Colors</option>';
                    $("#select-color-fold").append(option);
                    $('option[value='+(-1)+']').attr('selected', 'selected');
                }
                // Redraw the select element
                $("#select-color-fold").selectmenu('refresh');
                return;
            },
            error: function(jqXHR, status, error) {
                console.log("failure:"+jqXHR.responseText);
                return;
            }});


    /*
        Handle submitting form
     */
    $("input[type=submit]").click(function(e) {
        e.preventDefault();

        // Collect form data
        var colorID = $("#select-color-fold").val();
        var num_tops = $("#slider-tops-fold").val();
        var num_bottoms = $("#slider-bottoms-fold").val();
        var num_socks = $("#slider-socks-fold").val();
        var num_towels = $("#slider-towels-fold").val();
        var hang_tops = $("#slider-tops-hung").val();
        var hang_bottoms = $("#slider-bottoms-hung").val();

        var json_str = '{ ' +
            '"colorID":'+colorID + ',' +
            '"eid":'+eid + ',' +
            '"tops":'+num_tops + ',' +
            '"bottoms":'+num_bottoms + ',' +
            '"socks":'+num_socks + ',' +
            '"other":'+num_towels + ',' +
            '"hang_tops":'+hang_tops + ',' +
            '"hang_bottoms":'+hang_bottoms + "}";
        console.log(json_str);
        var obj = JSON.parse(json_str);

        var folding_json = null;
        $.ajax(url_base + "/fold.php/",
            {type: "POST",
                async: false,
                dataType: "json",
                data: obj,
                success: function(fold_json, status, jqXHR) {
                    folding_json = fold_json;
                },
                error: function(jqXHR, status, error) {
                    console.log("failure:"+jqXHR.responseText);
                    console.log("error:"+error);
                }});

        if (folding_json != null){
            $.ajax(url_base + "/sort.php/" + folding_json['lid'],
                {type: "GET",
                    async: false,
                    success: function(sort_json, status, jqXHR) {
                        if (sort_json['tops'] != folding_json['tops']){
                            alert("Tops inconsistent:"+sort_json['tops']);
                        }
                        if (sort_json['bottoms'] != folding_json['bottoms']){
                            alert("Bottoms inconsistent:"+sort_json['bottoms']);
                        }
                        if (sort_json['socks'] != folding_json['socks']){
                            alert("Socks inconsistent:"+sort_json['socks']);
                        }
                        if (sort_json['other'] != folding_json['other']){
                            alert("Towels/Other inconsistent:"+sort_json['other']);
                        }
                    },
                    error: function(jqXHR, status, error) {
                        console.log("failure:"+jqXHR.responseText);
                        console.log("error:"+error);
                    }});
        }
        //window.location.href = "folding.html";
    });

    $("#start_folding").click(function(e) {
        $("#header-fold").text("Folding - "+$("#slider-id-num").val());
    });
});

