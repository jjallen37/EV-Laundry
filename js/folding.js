/**
 * sorting.js
 */

$(document).ready(function() {

    $("input[type=submit]").click(function(e) {
        // Collect form data
        var name = $("#select-customer-name-fold option:selected").text();
        var time_ended = $("#time-ended").val();
        var num_tops = $("#slider-tops-fold").val();
        var num_bottoms = $("#slider-bottoms-fold").val();
        var num_socks = $("#slider-socks-fold").val();
        var num_towels = $("#slider-towels-fold").val();

        // For now, just print it
        console.log("Folding");
        console.log("Name:"+name);
        console.log("Time Started:"+time_ended);
        console.log("Tops:"+num_tops);
        console.log("Bottoms:"+num_bottoms);
        console.log("Socks:"+num_socks);
        console.log("Towels:"+num_towels);

        e.preventDefault();
    });
});

