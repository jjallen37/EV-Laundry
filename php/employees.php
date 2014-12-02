<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:39 AM
 */

ini_set('display_errors', 'On');
require_once('orm/Employee.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /customers.php/<id>
    // Return laundry with all components
    if (count($path_components) >= 2 && $path_components[1] != "") {
        $eid = intval($path_components[1]);
        $employee = Customer::findByID($eid);
        if ($employee == null) {
            header("HTTP/1.0 400 Bad Request");
            print("Bad EID");
            exit();
        }

        //Generate JSON encoding of new Review
        header("Content-type: application/json");
        print($employee->getJSON());
        exit();
    }
} else  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /employee.php/
    // Creates new count
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        // POST to /employee.php/ to create new employee in db
        // Validate values
        if (!isset($_REQUEST['name'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing employee name");
            exit();
        }
        $name = $_REQUEST['name'];

        $employee = Employee::create($name);
        // Report if failed
        if ($employee == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new employee object.");
            exit();
        }

        //Generate JSON encoding of new Count
        header("Content-type: application/json");
        print($employee->getJSON());
        exit();
    }
}
header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();
?>
