/**
 * login.js
 *
 * This controls the logic behind employees switching
 * identities for activities and tasks.
 */

$(document).ready(function() {
    // Populate employee list
    var url_base = "php";
    var $select = $("#select-employee");

    // Returns all eids
    $.ajax(url_base + "/employees.php/",
        {
            type: "GET",
            success: function(eids, status, jqXHR) {
                console.log('eids:'+eids);
                $.each(eids, function (i, eid){
                    addEmployeeDiv(eid);
                });
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching employee list:"+jqXHR.responseText);
            }});

    function addEmployeeDiv(eid){
        // Returns
        $.ajax(url_base + "/employees.php/" + eid,
            {
                type: "GET",
                success: function(employee_json, status, jqXHR) {
                    var employee = new Employee(employee_json);
                    var option = '<option value="'+employee.eid+'">'+employee.name+'</option>';
                    $select.append(option);
                    $select.selectmenu('refresh');
                },
                error: function(jqXHR, status, error) {
                    console.log("Error fetching employee :"+eid+"..."+jqXHR.responseText);
                }});

    }

    $("input[type=submit]").click(function(e) {
        e.preventDefault();

        var eid = $select.val();
        var user = $select.find('option[value="'+eid+'"]').text();

        // NOTE: Will hang when used in incognito...in Safari
        if(typeof(Storage) !== "undefined") {
            sessionStorage.username = user;
            sessionStorage.eid = eid;
        } else {
            alert("Sorry, your browser does not support web storage...");
            return;
        }

        // Redirect browser to selected task
        var task = $("#select-task").prop('selectedIndex');
        switch (task){
            case 0:
                window.location.href = "sorting.html";
                break;
            case 1:
                window.location.href = "washing.html";
                break;
            case 2:
                window.location.href = "drying.html";
                break;
            case 3:
                window.location.href = "folding.html";
                break;
            default:
                return;
        }
    });
});

