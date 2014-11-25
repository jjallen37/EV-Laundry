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
}

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();
?>
