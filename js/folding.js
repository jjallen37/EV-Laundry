/**
 * sorting.js
 */

$(document).ready(function() {

    var time_started;
    var time_finished;

    $("input[type=submit]").click(function(e) {
        time_finished = Date.now();
        // Collect form data
        var name = $("#select-customer-name-fold option:selected").text();
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

    //$("#slider-tops-fold").on('change mousemove',function(){
    //    var newMax = $(this).val();
    //    resetSlider(newMax);
    //});
    //
    //function resetSlider(maxPrice){
    //    $("#slider-tops-hung").attr('value',maxPrice);
    //    $("input[type='range']").slider( "refresh" );//refresh slider to max value
    //    $(".ui-slider-handle").css("left","100%");
    //    console.log("Slider Reset Done.");
    //}

    $("#start_folding").click(function(e) {
        $("#header-fold").text("Folding - "+$("#slider-id-num").val());
        time_started = Date.now();
    });
    console.log("page ready");
});

