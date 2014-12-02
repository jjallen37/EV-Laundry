/**
 * Created by jjallen on 11/18/14.
 */

var Laundry = function(laundry_json) {
    this.lid = laundry_json.lid;
    this.cid = laundry_json.cid;
    this.color = laundry_json.color;

    var url_base = "php";

    var tmp1;
    var tmp2;

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
    var json_str = '{ ' +
        '"lid":' + this.lid + ',' +
        '"isFold":' + 0 + '}';
    var obj = JSON.parse(json_str);
    $.ajax(url_base + "/clothes.php/",
        {
            type: "GET",
            async: false,
            data: obj,
            success: function(json, status, jqxhr) {
                tmp2 = new Clothes(json);
            },

            error: function(jqxhr, status, error) {
                console.log("failure2:"+jqxhr.responseText);
            }
        });
    this.sort = tmp2;
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
