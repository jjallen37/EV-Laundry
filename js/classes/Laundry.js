/**
 * Created by jjallen on 11/18/14.
 */

var Laundry = function(laundry_json) {
    this.lid = laundry_json.lid;
    this.cid = laundry_json.cid;
    this.color_id = laundry_json.color_id;

    var url_base = "php";

    var tmp1 = "";
    var tmp2 = "";
    var tmp3 = "";

    //// Grab customer data from REST
    //$.ajax(url_base + "/customers.php/" + this.cid + "/",
    //    {
    //        type: "GET",
    //        async: false,
    //        success: function (json, status, jqxhr) {
    //            tmp1 = new Customer(json);
    //        },
    //
    //        error: function (jqxhr, status, error) {
    //            console.log("failure1:" + jqxhr.responseText);
    //        }
    //    });
    //this.customer = tmp1;

    // Grab sort data from REST
    //var json_str = '{ ' +
    //    '"lid":' + this.lid + ',' +
    //    '"isFold":' + 0 + '}';
    //var obj = JSON.parse(json_str);
    //$.ajax(url_base + "/counts.php/",
    //    {
    //        type: "GET",
    //        async: false,
    //        data: obj,
    //        success: function(json, status, jqxhr) {
    //            tmp2 = new Clothes(json);
    //        },
    //
    //        error: function(jqxhr, status, error) {
    //            console.log("failure2:"+jqxhr.responseText);
    //        }
    //    });
    //this.sort = tmp2;

    // Grab sort data from REST
    //json_str = '{ ' +
    //    '"lid":' + this.lid + ',' +
    //    '"isFold":' + 1 + '}';
    //obj = JSON.parse(json_str);
    //$.ajax(url_base + "/counts.php/",
    //    {
    //        type: "GET",
    //        async: false,
    //        data: obj,
    //        success: function(json, status, jqxhr) {
    //            tmp3 = new Clothes(json);
    //        },
    //
    //        error: function(jqxhr, status, error) {
    //            console.log("failure3:"+jqxhr.responseText);
    //        }
    //    });
    //this.fold = tmp3;
};

Laundry.LaundryStatus = {
    ACTIVE : 'ACTIVE',
    DONE : 'DONE',
    ERROR : 'ERROR'
};

Laundry.prototype.getEvents = function() {
    var events = [];
    $.ajax(url_base + "/laundry.php/" + this.lid + "/events/",
        {type: "GET",
            async: false,
            success: function(event_ids, status, jqXHR) {
                $.each(event_ids, function (i, event_id){
                    var event = findEventByID(event_id);
                    events.push(event);
                });
            },
            error: function(jqXHR, status, error) {
                console.log("Failure finding laundry events:"+jqXHR.responseText);
            }});
    return events;
};

Laundry.prototype.getSort = function() {
    var events = this.getEvents();
    var sort_id = -1;
    events.forEach(function(event){
        if (event.event_action == Event.ActionEnum.SORT){
            sort_id = event.id;
        }
    });
    return findCountByID(sort_id);
};

Laundry.prototype.getFold = function() {
    var events = this.getEvents();
    var fold_id = -1;
    events.forEach(function(event){
        if (event.event_action == Event.ActionEnum.FOLD){
            fold_id = event.id;
        }
    });
    return findCountByID(fold_id);
};

Laundry.prototype.updateStatus = function() {
    var json_str = '{ ' +
        '"cid"      : ' + this.cid + ',' +
        '"color_id" : ' + this.color_id + ',' +
        '"status"   : "' + 'DONE' + '"}';
    var obj = JSON.parse(json_str);
    $.ajax(url_base + "/laundry.php/" + this.lid + "/",
        {type: "POST",
            async: false,
            dataType: "json",
            data: obj,
            success: function(laundry_json, status, jqXHR) {
                console.log("Laundry updated successfully");
            },
            error: function(jqXHR, status, error) {
                console.log("Failure updating laundry:"+jqXHR.responseText);
            }});
};

Laundry.prototype.makeCompactLi = function() {
    return $('<li/>', {    //here appending `<li>`
        'id': 'laundry-li'+this.lid
    }).append($('<a/>', {    //here appending `<a>` into `<li>`
        'href': 'laundry_view.php?lid='+this.lid,
        'data-transition': 'slide',
        'text': this.lid
    }));
};


function createLaundry(cid, color_id){
    // Insert request
    var json_str = '{ ' +
        '"cid"      : ' + cid + ',' +
        '"color_id" : ' + color_id + ',' +
        '"status"   : "' + Laundry.LaundryStatus.ACTIVE + '"}';
    var obj = JSON.parse(json_str);
    var laundry = null;
    $.ajax(url_base + "/laundry.php/",
        {type: "POST",
            async: false,
            dataType: "json",
            data: obj,
            success: function(laundry_json, status, jqXHR) {
                laundry = new Laundry(laundry_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure creating laundry:"+jqXHR.responseText);
            }});
    return laundry;
}

function findLaundryByID(lid){
    // Insert request
    var laundry = null;
    $.ajax(url_base + "/laundry.php/" + lid,
        {type: "GET",
            async: false,
            success: function(laundry_json, status, jqXHR) {
                laundry = new Laundry(laundry_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure finding laundry by id:"+lid+"..."+jqXHR.responseText);
            }});
    return laundry;
}
