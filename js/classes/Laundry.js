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

    // Grab customer data from REST
    $.ajax(url_base + "/customers.php/" + this.cid + "/",
        {
            type: "GET",
            async: false,
            success: function (json, status, jqxhr) {
                tmp1 = new Customer(json);
            },

            error: function (jqxhr, status, error) {
                console.log("failure1:" + jqxhr.responseText);
            }
        });
    this.customer = tmp1;

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

Laundry.prototype.makeCompactDiv = function() {
    var laundryDiv = $("<div></div>");
    laundryDiv.addClass('laundry_cell');

    laundryDiv.text("LID:"+this.lid);
    //laundryDiv.append("<br>");
    //laundryDiv.append("Customer Name:"+this.customer.name);
    //
    //if (this.sort != null){
    //    laundryDiv.append(this.sort.makeCompactDiv());
    //}
    return laundryDiv;
};

function practice(spagett, target){
    var collection = 23;
    practice(spagett,target);
}
