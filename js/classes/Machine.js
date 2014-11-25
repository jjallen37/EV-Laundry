/**
 * Created by jjallen on 11/24/14.
 */

var url_base = "php";

var Machine = function(machine_json){
    this.isDryer = machine_json.isDryer;
    this.isLoad = machine_json.isLoad;
    this.num = machine_json.num;
    //this.lid = machine_json.lid;

    //// Grab customer data from REST
    //var tmp1;
    //$.ajax(url_base + "/laundry.php/" + this.lid + "/",
    //    {
    //        type: "GET",
    //        async: false,
    //        success: function (json, status, jqxhr) {
    //            tmp1 = new Laundry(json);
    //        },
    //
    //        error: function (jqxhr, status, error) {
    //            console.log("Failure loading laundry:" + jqxhr.responseText);
    //        }
    //    });
    //this.laundry = tmp1;
};

Machine.prototype.makeCompactDiv = function() {
    var machine_div = $("<div></div>");
    machine_div.addClass("machine_div");

    if (this.isDryer){
        machine_div.text("Washer " + this.num + "");
    } else {
        machine_div.text("Dryer " + this.num);
    }

    return machine_div;
};
