<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:39 AM
 */

ini_set('display_errors', 'On');
require_once('orm/Customer.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /customers.php/<id>
    // Return laundry with all components
    if (count($path_components) >= 2 && $path_components[1] != "") {
        $cid = intval($path_components[1]);
        $customer = Customer::findByID($cid);
        if ($customer == null) {
            header("HTTP/1.0 400 Bad Request");
            print("Bad CID");
            exit();
        }

        //Generate JSON encoding of new Review
        header("Content-type: application/json");
        print($customer->getJSON());
        exit();
    }
} else  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /customer.php/
    // Creates new count
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        // POST to /customer.php/ to create new customer in db
        // Validate values
        if (!isset($_REQUEST['name'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing customer name");
            exit();
        }
        $name = $_REQUEST['name'];

        $customer = Customer::create($name);
        // Report if failed
        if ($customer == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new customer object.");
            exit();
        }

        //Generate JSON encoding of new Count
        header("Content-type: application/json");
        print($customer->getJSON());
        exit();
    }
}
header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();
?>

