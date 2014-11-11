/**
 * sorting.js
 */

$(document).ready(function() {

    var time_started;
    var time_finished;

    $("input[type=submit]").click(function(e) {
        time_finished = Date.now();

        // Collect form data
        var name = $("#select-customer-name option:selected").text();
        var num_tops = $("#slider-tops-sort").val();
        var num_bottoms = $("#slider-bottoms-sort").val();
        var num_socks = $("#slider-socks-sort").val();
        var num_towels = $("#slider-towels-sort").val();

        // For now, just print it
        console.log("Sorting");
        console.log("Name:"+name);
        console.log("Tops:"+num_tops);
        console.log("Bottoms:"+num_bottoms);
        console.log("Socks:"+num_socks);
        console.log("Towels:"+num_towels);


        alert("Time started:"+time_started);
        alert("Time finished:"+time_finished);

        e.preventDefault();

    });

    $("#start_sorting").click(function(e) {
        $("#header-sort").text("Sorting - "+$("#slider-id-num").val());
        time_started = Date.now();

        $.ajax(url_base + "/laundry.php/",
            {type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function(review_json, status, jqXHR) {
                    window.location.href = "http://wwwx.cs.unc.edu/Courses/comp426-f13/jamesml/site/bathroomview.php?bid="+bid;
                },
                error: function(jqXHR, status, error) {
                    // alert("faliure:"+jqXHR.responseText);
                }});
    });

});

