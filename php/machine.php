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
$NUM_DRYERS = 6;

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /machine.php/
    // Either unload or load a machine
    // isDryer
    if (!isset($_REQUEST['isDryer'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing washer/dryer flag");
        exit();
    }
    $isDryer = intval($_REQUEST['isDryer']);

    // isLoad
    if (!isset($_REQUEST['isLoad'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing load/unload flag");
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

    // Machine Number
    if (!isset($_REQUEST['num'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing machine number");
        exit();
    }
    $num = intval($_REQUEST['num']);
    $NUM_MAX = $isDryer ? $NUM_DRYERS : $NUM_WASHERS;
    if ($num < 1 || $num > $NUM_MAX){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid machine number : " . $num);
        exit();
    }

    // Verify that this is a legal operation
    $currentMachine = Machine::getMachine($isDryer, $num);
    // Currently the machine is loaded, expecting an unload
    if ($currentMachine->isLoad() && $isLoad){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid load command on full machine");
        exit();
    }
    if (!$currentMachine->isLoad() && !$isLoad){ // Empty -> Empty
        header("HTTP/1.0 400 Bad Request");
        print("Invalid unload command on empty machine");
        exit();
    }
    // Loaded -> Empty
    if ($currentMachine->isLoad() &&
        $currentMachine->getLID() != $lid){ // Unloading a different laundry
            header("HTTP/1.0 400 Bad Request");
            print("Invalid request. Can not unload with different laundry id.");
            exit();
    }

    // Create new Review via ORM
    $machine = Machine::create($isDryer, $isLoad, $lid, $eid, $num);

    // Report if failed
    if ($machine == null) {
        header("HTTP/1.0 500 Server Error");
        print("Server couldn't complete machine action.");
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($machine->getJSON());
    exit();
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /machine.php - Currently unsupported
    if (!(count($path_components) >= 3 &&
        $path_components[1] != "" &&
        $path_components[2] != "")) {
        header("HTTP/1.0 400 Bad Request");
        print("No support for GET /machine.php/");
        exit();
    }

    // GET to /machine.php/(washer | dryer)/ < machine number >
    // Returns data on machine
    if ($path_components[1] == "washer"){
        $isDryer = 0;
        $NUM_MAX = $NUM_WASHERS;
    } else if ($path_components[1] == "dryer"){
        $isDryer = 1;
        $NUM_MAX = $NUM_DRYERS;
    } else {
        header("HTTP/1.0 400 Bad Request");
        print("Invalid machine type : " . $path_components[1]);
        exit();
    }

    // Washer Number
    $num = intval($path_components[2]);
    if ($num < 1 || $num > $NUM_MAX){
        header("HTTP/1.0 400 Bad Request");
        print("Invalid machine number : " . $num);
        exit();
    }

    $machine = Machine::getMachine($isDryer, $num);
    if ($machine == null){
        header("HTTP/1.0 404 Not Found");
        print("No previous data for machine : " . $num);
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($machine->getJSON());
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>
