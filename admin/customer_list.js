/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";

//$(document).on('pagecreate',function() {
$(document).ready(function() {
    var csul = $('#customers_ul');
    console.log("customers page before show");
    csul.empty();
    csul.listview('refresh');
    // Fetch the customer list and async
    var text = "";
    $.ajax(url_base + "php/customers.php/",
        {type: "GET",
            async:false,
            dataType: "json",
            success: function(cids, status, jqXHR) {
                if (cids===null) {
                    text = document.createTextNode("No Customers in Database.");
                } else {
                    // Add each customer async
                    for (var i=0; i<cids.length; i++) {
                        addCustomer(cids[i]);
                    }
                }
            },
            error: function(jqXHR, status, error) {
                text = document.createTextNode("Error fetching customer ids:"+jqXHR.responseText);
            }});
    $('#customer_status').html(text);
});


function addCustomer(cid) {
    $.ajax(url_base + "php/customers.php/" + cid, {
        type: "GET",
        dataType: "json",
        success: function (customer_json, status, jqXHR) {
            console.log("Display Customer: " + cid);
            var c = new Customer(customer_json);
            csul.append(c.makeCompactLi());
            csul.listview('refresh');
        },
        error: function(jqXHR, status, error) {
            console.log("Cid:"+cid+"....Response"+jqXHR.responseText);
        }
    });
}
