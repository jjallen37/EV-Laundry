/**
 * Created by jjallen on 11/24/14.
 */

var url_base = "php";

var Event = function(event_json) {
    this.event_id = event_json.event_id;
    this.lid = event_json.lid;
    this.eid = event_json.eid;
    this.id = event_json.id;
    this.event_action = event_json.event_action;
    this.timestamp = event_json.timestamp;
    this.isDryer = (this.event_action == Event.ActionEnum.LOAD_DRY) ||
                    (this.event_action == Event.ActionEnum.UNLOAD_DRY);
    this.isLoad = (this.event_action == Event.ActionEnum.LOAD_DRY) ||
                    (this.event_action == Event.ActionEnum.LOAD_WASH);
};

Event.ActionEnum = {
    SORT : 0,
    LOAD_WASH : 1,
    UNLOAD_WASH : 2,
    LOAD_DRY : 3,
    UNLOAD_DRY : 4,
    FOLD : 5
};

Event.prototype.makeMachineDiv = function() {
    var machine_div = $('<div></div>');
    machine_div.addClass("machine_div");

    // Cell Header
    var header_div = $('<div></div>');
    header_div.addClass("machine_header");
    var title;
    var type;
    if (this.isDryer){
        type = "Dryer";
        title = document.createTextNode(type + " " + this.num);
    } else {
        type = "Washer";
        title = document.createTextNode(type + " " + this.num);
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
        bodyText = $('<h3>Click to Unload '+type+'</h3>');
    } else { // The machine is empty, needs loading
        bodyText = $('<h3>Click to Load '+type+'</h3>');
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

function findEventByID(event_id){
    // Insert request
    var event = null;
    $.ajax(url_base + "/events.php/" + event_id + "/",
        {type: "GET",
            async: false,
            success: function(event_json, status, jqXHR) {
                console.log("Event json received");
                event = new Event(event_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure finding event by id:"+event_id+"..."+jqXHR.responseText);
            }});
    return event;
}

function createEvent(lid, eid, id, event_action, timestamp){
    // Insert request
    var json_str = '{ ' +
        '"lid"          : ' + lid + ',' +
        '"eid"          : ' + eid + ',' +
        '"id"           : ' + id + ',' +
        '"event_action" : ' + event_action + ',' +
        '"timestamp"    : ' + timestamp + '}';
    var obj = JSON.parse(json_str);
    var event = null;
    $.ajax(url_base + "/events.php/",
        {   type: "POST",
            async: false,
            dataType: "json",
            data: obj,
            success: function(event_json, status, jqXHR) {
                event = new Event(event_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure creating event:"+jqXHR.responseText);
            }});
    return event;
}
