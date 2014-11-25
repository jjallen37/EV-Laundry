/**
 *
 * Created by jjallen on 11/20/14.
 */

var dryer_ids = ["#dryer-1","#dryer-2","#dryer-3","#dryer-4","#dryer-5","#dryer-6"];
var dryers = [];
var NUM_DRYERS = 6;
var current_dryer = -1;

$(document).ready(function() {
    /*
     Initialize dryers
     */

    console.log("adding divs");
    for (var i = 0; i < NUM_DRYERS; i++){
        var tmp = '{' +
            '"isDryer" : 1' + ',' +
            '"isLoad"  : 0' + ',' +
            '"num"     : ' + (i+1) +
            '}';
        var obj = JSON.parse(tmp);
        dryers.push(obj);
        var washer_div = new Machine(obj).makeCompactDiv();
        $(dryer_ids[i]).append(washer_div);

        $(dryer_ids[i]).attr("data-rel","popup");
        $(dryer_ids[i]).attr("data-position-to","window");
        $(dryer_ids[i]).attr("data-transition","pop");

        // If the object is waiting to unload
        if (obj['isLoad']){
            $(dryer_ids[i]).prop("href","#unload_dry_popup");
        } else {
            $(dryer_ids[i]).prop("href","#load_dry_popup");
        }

        $(dryer_ids[i]).on('click', function (e) {
            current_dryer = $(this).attr('id').slice(-1) - 1;
        });
    }

    // Load washer
    $("#load-dry-submit-btn").click(function(e){
        console.log("Load Dryer - :" + current_dryer);
        console.log("With color:" + $("#select-color-dload").val());
        e.preventDefault();
        $('#load_dryer_popup').popup('close');
    });

    // Unload washer
    $("#unload-washer-submit-btn").click(function(e){
        console.log("Unload Dryer - :" + current_dryer);
        e.preventDefault();
        $('#unload_dry_popup').popup('close');
    });
});

