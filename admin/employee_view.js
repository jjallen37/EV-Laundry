/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";
$(document).on('pagecreate',function() {
    var eid = $('#eid').val(); // Saved from php
    var eul = $('#employee_ul');

    // Load the employee header
    $.ajax(url_base + "php/employees.php/" + eid, {
        type: "GET",
        dataType: "json",
        async: false,
        success: function (employee_json, status, jqXHR) {
            var e = new Employee(employee_json);
            $('#employee_header').text(e.name);
            eul.listview('refresh');
        },
        error: function(jqXHR, status, error) {
            //TODO error handle
            $('#employee_header').html("Error loading employee:"+eid);
        }
    });

    // TODO
    eul.append($('<li>No Employee Event Listing Yet</li>'));
    eul.listview('refresh');
});

//TODO
function addEvent(id) {
}
