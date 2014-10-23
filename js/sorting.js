/**
 * sorting.js
 */

$(document).ready(function() {

    $("input[type=submit]").click(function(e) {
        // Collect form data
        var name = $("#select-customer-name option:selected").text();
        var time_started = $("#time-started").val();
        var num_tops = $("#slider-tops-sort").val();
        var num_bottoms = $("#slider-bottoms-sort").val();
        var num_socks = $("#slider-socks-sort").val();
        var num_towels = $("#slider-towels-sort").val();

        // For now, just print it
        console.log("Sorting");
        console.log("Name:"+name);
        console.log("Time Started:"+time_started);
        console.log("Tops:"+num_tops);
        console.log("Bottoms:"+num_bottoms);
        console.log("Socks:"+num_socks);
        console.log("Towels:"+num_towels);

        e.preventDefault();
    });
});

