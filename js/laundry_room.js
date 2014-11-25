/**
 *
 * Created by jjallen on 11/20/14.
 */

var washer_ids = ["#washer-1","#washer-2","#washer-3","#washer-4"];
var washers = [];
var dryers = [];
var NUM_WASHERS = 4;
var NUM_DRYERS = 6;


$(document).ready(function() {
    /*
        Initialize washers and dryers?
     */
    console.log("Spagett");

    /*
    for (var i = 0; i < NUM_WASHERS; i++){
        var tmp = '{' +
            '"isDryer" : 0' + ',' +
            '"isLoad"  : 0' + ',' +
            '"num"     : ' + (i+1) +
            '}';
        var obj = JSON.parse(tmp);
        var washer_div = new Machine(obj).makeCompactDiv();
        $("#washers_div").append(washer_div);
    }
    */

    /*
        Use each machine instance to create dyers/washers_div
     */
});
