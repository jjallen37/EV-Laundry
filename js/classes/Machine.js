/**
 * Created by jjallen on 11/24/14.
 */

var url_base = "php";

var Machine = function(machine_json){
    this.isDryer = machine_json.isDryer;
    this.isLoad = machine_json.isLoad;
    this.num = machine_json.num;
    this.thyme = machine_json.thyme;
    this.lid = machine_json.lid;
    this.eid = machine_json.eid;

    // Grab customer data from REST
    var tmp1 = null;
    if (this.lid > 0) {
        $.ajax(url_base + "/laundry.php/" + this.lid + "/",
            {
                type: "GET",
                async: false,
                success: function (json, status, jqxhr) {
                    tmp1 = new Laundry(json);
                },

                error: function (jqxhr, status, error) {
                    console.log("Failure loading laundry:" + jqxhr.responseText);
                }
            });
    }
    this.laundry = tmp1;
};

Machine.prototype.makeCompactDiv = function() {
    var machine_div = $('<div></div>');
    machine_div.addClass("machine_div");

    // Cell Header
    var header_div = $('<div></div>');
    header_div.addClass("machine_header");
    var title;
    if (this.isDryer){
        title = document.createTextNode("Dryer " + this.num);
    } else {
        //title = document.createTextNode("Washer " + this.num + " unloaded by Ernest Employee Extraordinary at 8:35 AM");
        title = document.createTextNode("Washer " + this.num);
    }
    header_div.append(title);
    header_div.append($('<hr>'));

    // Append the header div
    machine_div.append(header_div);

    var body_div = $('<div></div>');
    body_div.addClass("machine_body");
    var bodyText;
    if (this.isLoad){ // The machine is full, needs unloading
        // Color of laundry in machine
        var colorbox_div = $('<div></div>');
        colorbox_div.addClass("machine_color");
        console.log("color:"+this.laundry.color);
        switch (parseInt(this.laundry.color)){
            case 1: // Red
                colorbox_div.css("background-color", "red");
                break;
            case 2: // Blue
                colorbox_div.css("background-color", "blue");
                break;
            case 3: // Green
                colorbox_div.css("background-color", "green");
                break;
            case 4: // Orange
                colorbox_div.css("background-color", "orange");
                console.log("Set css as orange");
                break;
            default:
                console.log("Default reached");
                break;
        }
        machine_div.append(colorbox_div);
        bodyText = $('<h3>Click to Unload Washer</h3>');
    } else { // The machine is empty, needs loading
        bodyText = $('<h3>Click to Load Washer</h3>');
    }
    body_div.append(bodyText);
    machine_div.append(body_div);

    // Show detail about last transaction if there is any
    if (this.lid > 0){
        var footer_div = $('<div></div>');
        footer_div.addClass("machine_footer");
        var action = this.isLoad ? "loaded" : "unloaded";
        var employee = "Temp Employee";
        var msg = "Last " + action + " by " + employee + " at " + this.thyme;
        footer_div.append(document.createTextNode(msg));
        machine_div.append(footer_div);
    }

    return machine_div;
};

