/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";
$(document).on('pageload',function() {
    var cid = $('#cid').val(); // Saved from php
    var cul = $('#customer_ul');
    cul.empty();

    // Load the customer header
    $.ajax(url_base + "php/customers.php/" + cid, {
        type: "GET",
        dataType: "json",
        async: false,
        success: function (customer_json, status, jqXHR) {
            var c = new Customer(customer_json);
            $('#customer_header').text(c.name);
            cul.listview('refresh');
        },
        error: function(jqXHR, status, error) {
            //TODO error handle
            $('#customer_header').html("Error loading customer:"+cid);
        }
    });

    // TODO
    cul.append($('<li>No Customer Event Listing Yet</li>'));
    cul.listview('refresh');
});

//TODO
function addEvent(id) {
}
