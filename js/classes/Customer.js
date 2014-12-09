/**
 * Created by jjallen on 11/20/14.
 */

var Customer = function(customer_json){
    this.cid = customer_json.cid;
    this.name = customer_json.name;
};

Customer.prototype.makeCompactLi = function() {
    return $('<li/>', {    //here appending `<li>`
        'id': 'customer-li'+this.cid
    }).append($('<a/>', {    //here appending `<a>` into `<li>`
        'href': 'customer_view.php?cid='+this.cid,
        'data-transition': 'slide',
        'text': this.name
    }));
};

Customer.prototype.makeSelectOption = function() {
    return '<option value="'+this.cid+'">'+this.name+'</option>';
};

function findCustomerByID(id){
    var url_base = "php";
    var customer = null;
    $.ajax(url_base + "/customers.php/" + id,
        {
            type: "GET",
            async: false,
            success: function(customer_json, status, jqXHR) {
                customer = new Customer(customer_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching available customer id:"+id+
                "..."+jqXHR.responseText);
            }});
    return customer;
};

