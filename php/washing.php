<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Machine.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);
$NUM_WASHERS = 4;

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /washing.php/
    // Either unload or load a washer
    // isLoad
    if (!isset($_REQUEST['isLoad'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing load/unload");
        exit();
    }
    $isLoad = intval($_REQUEST['isLoad']);

    // Laundry id
    if (!isset($_REQUEST['lid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing laundry id");
        exit();
    }
    $lid = intval($_REQUEST['lid']);
    //TODO More error checking on lid?

    // Employee id
    if (!isset($_REQUEST['eid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing employee id");
        exit();
    }
    $eid = intval($_REQUEST['eid']);

    // Washer Number
    if (!isset($_REQUEST['num'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing washer number");
        exit();
    }
    $num = intval($_REQUEST['num']);
    if ($num < 1 || $num > $NUM_WASHERS){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid washer number : " . $num);
        exit();
    }

    // Verify that this is a legal operation
    $currentWasher = Machine::getWasher($num);
    // Currently the washer is loaded, expecting an unload
    if ($currentWasher->isLoad() && $isLoad){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid load command on full washer");
        exit();
    }
    if (!$currentWasher->isLoad() && !$isLoad){ // Empty -> Empty
        header("HTTP/1.0 400 Bad Request");
        print("Invalid unload command on empty washer");
        exit();
    }
    // Loaded -> Empty
    if ($currentWasher->isLoad() &&
        $currentWasher->getLID() != $lid){ // Unloading a different laundry
            header("HTTP/1.0 400 Bad Request");
            print("Invalid request. Can not unload with different laundry id.");
            exit();
    }

    // Create new Review via ORM
    $washer = Machine::create(0, $isLoad, $lid, $eid, $num);

    // Report if failed
    if ($washer == null) {
        header("HTTP/1.0 500 Server Error");
        print("Server couldn't complete washer action.");
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($washer->getJSON());
    exit();
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /washing.php/ - Currently unsupported
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        header("HTTP/1.0 400 Bad Request");
        print("No support for GET /washing.php/");
        exit();
    }

    // Washer Number
    $num = intval($path_components[1]);
    if ($num < 1 || $num > $NUM_WASHERS){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid washer number : " . $num);
        exit();
    }

    $washer = Machine::getWasher($num);
    if ($washer == null){
        header("HTTP/1.0 404 Not Found");
        print("No previous data for washer : " . $num);
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($washer->getJSON());
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>
