<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Event.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);
$NUM_WASHERS = 4;
$NUM_DRYERS = 6;

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

// POST to /events.php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Laundry id
    if (!isset($_REQUEST['lid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing laundry id");
        exit();
    }
    $lid = intval($_REQUEST['lid']);
    // Employee id
    if (!isset($_REQUEST['eid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing employee id");
        exit();
    }
    $eid = intval($_REQUEST['eid']);
    // Event Action
    if (!isset($_REQUEST['event_action'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing event action.");
        exit();
    }
    $event_action = intval($_REQUEST['event_action']);
    // Clothes id / Machine Number
    if (!isset($_REQUEST['id'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing id associated with event.");
        exit();
    }
    $id = intval($_REQUEST['id']);
    // Clothes id / Machine Number
    if (!isset($_REQUEST['timestamp'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing timestamp associated with event.");
        exit();
    }
    $timestamp = intval($_REQUEST['timestamp']);

    // TODO Verify action validity
//    // Verify that this is a legal operation
//    $currentMachine = Machine::getMachine($isDryer, $num);
//
//    // Currently the machine is loaded, expecting an unload
//    if ($currentMachine->isLoad() && $isLoad){
//        header("HTTP/1.0 400 Bad Request");
//        print("Invalid load command on full machine");
//        exit();
//    }
//    if (!$currentMachine->isLoad() && !$isLoad){ // Empty -> Empty
//        header("HTTP/1.0 400 Bad Request");
//        print("Invalid unload command on empty machine");
//        exit();
//    }

//    // Loaded -> Empty
//    if ($currentMachine->isLoad() &&
//        $currentMachine->getLID() != $lid){ // Unloading a different laundry
//            header("HTTP/1.0 400 Bad Request");
//            print("Invalid request. Can not unload with different laundry id.");
//            exit();
//    }

    // Create new Review via ORM
    $event = Event::create($lid, $eid, $id, $event_action,$timestamp);

    // Report if failed
    if ($event == null) {
        header("HTTP/1.0 500 Server Error");
        print("Server couldn't create event.");
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($event->getJSON());
    exit();
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET events.php/
    // Return all event ids
    if (count($path_components) < 2 || $path_components[1] == "") {
        //Generate JSON encoding of all event ids
        header("Content-type: application/json");
        print(json_encode(Event::getAllIDs()));
        exit();
    }

    // events.php/<id>
    $id_array = array();
    $id = intval($path_components[1]);
    $event = Event::findByID($id);

    if ($event == null) {
        header("HTTP/1.0 500 Server Error");
        print("Could not find event with event_id:" . $id);
        exit();
    }

    //Generate JSON encoding of new Laundry
    header("Content-type: application/json");
    print($event->getJSON());
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>
