/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";
$(document).on('pagebeforeshow',function() {
    $('#employees_ul').empty();
    // Fetch the employee list and async
    var text = "";
    $.ajax(url_base + "php/employees.php/",
        {type: "GET",
            async:false,
            dataType: "json",
            success: function(eids, status, jqXHR) {
                if (eids===null) {
                    text = document.createTextNode("No Employees in Database.");
                } else {
                    // Add each employee async
                    for (var i=0; i<eids.length; i++) {
                        addEmployee(eids[i]);
                    }
                }
            },
            error: function(jqXHR, status, error) {
                text = document.createTextNode("Error fetching employee ids:"+jqXHR.responseText);
            }});
    $('#employee_status').html(text);
});


function addEmployee(eid) {
    $.ajax(url_base + "php/employees.php/" + eid, {
        type: "GET",
        dataType: "json",
        success: function (employee_json, status, jqXHR) {
            console.log("Display Employee: " + eid);
            var e = new Employee(employee_json);
            $('#employees_ul').append(e.makeCompactLi());
            $('#employees_ul').listview('refresh');

        },
        error: function(jqXHR, status, error) {
            console.log("Eid:"+eid+"....Response"+jqXHR.responseText);
        }
    });
}
