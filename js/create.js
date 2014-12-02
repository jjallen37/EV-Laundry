/**
 *
 * Created by jjallen on 12/1/14.
 */

var url_base = "php";

$(document).on('pagecreate',function() {

    // Create/clear the database
    $.ajax(url_base + "/create.php/",
        {
            type: "POST",
            async: false,
            success: function (success) {
                $("#other_result").html($('<p>Database Created</p>'));
            },
            error: function (jqXHR, status, error) {
                $("#other_result").html($('<p>Error Creating Databases</p>'));
            }
        });

    // Insert employee data from file
    var employee_div = $("#employees_result");
    $.ajax({
        type: "GET",
        url: "db/test_employees.csv",
        dataType: "text",
        success: function (data) {
            employee_div.html($('<p>Employees Loaded</p>'));
            processEmployees(data);
            employee_div.append($('<p>Done Loading Employees into DB</p>'));
        },
        error: function () {
            employee_div.html($('<p>Error Loading Employee Data</p>'));
        }
    });

    // Insert employee data from file
    var customer_div = $("#customers_result");
    $.ajax({
        async: false,
        type: "GET",
        url: "db/test_customers.csv",
        dataType: "text",
        success: function (data) {
            customer_div.html($('<p>Customers Loaded</p>'));
            processCustomers(data);
            customer_div.append($('<p>Done Loading Customers into DB</p>'));
        },
        error: function () {
            customer_div.html($('<p>Error Loading Customers Data</p>'));
        }
    });
});

function processCustomers(data) {
    var record_num = 1;  // or however many elements there are in each row
    var customer_div = $("#customer_result");
    var allTextLines = data.split(/\r\n|\n/);
    allTextLines.forEach(function (name) {
        if (name.trim() != "") {
            var json_str = '{ ' +
                '"name": "' + name + '"}';
            console.log(json_str);
            var obj = JSON.parse(json_str);
            $.ajax(url_base + "/customers.php/",
                {
                    type: "POST",
                    async: false,
                    dataType: "json",
                    data: obj,
                    error: function (jqXHR, status, error) {
                        customer_div.append($('<p>Failure inserting customer:' + name + '</p>'));
                    }
                });
        }
    });
}

function processEmployees(data) {
    var record_num = 1;  // or however many elements there are in each row
    var employee_div = $("#employees_result");
    var allTextLines = data.split(/\r\n|\n/);
    allTextLines.forEach(function (name) {
        if (name.trim() != "") {
            var json_str = '{ ' +
                '"name": "' + name + '"}';
            console.log(json_str);
            var obj = JSON.parse(json_str);
            $.ajax(url_base + "/employees.php/",
                {
                    type: "POST",
                    async: false,
                    dataType: "json",
                    data: obj,
                    error: function (jqXHR, status, error) {
                        employee_div.append($('<p>Failure inserting employee:' + name + '</p>'));
                    }
                });
        }
    });
}
