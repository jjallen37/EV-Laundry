<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Laundry.php');
require_once('orm/Event.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /laundry.php/ to create new laundry
    // Validate values
    if (!isset($_REQUEST['cid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing customer id");
        exit();
    }
    $cid = $_REQUEST['cid'];
    if (!isset($_REQUEST['color_id'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing a color_id");
        exit();
    }
    $color_id = intval($_REQUEST['color_id']);
    if (!isset($_REQUEST['status'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing laundry status");
        exit();
    }
    $status = $_REQUEST['status'];

    // POST /laundry.php/laundry
    if (count($path_components) < 2 || $path_components[1] == "") {
        // Create new Laundry via ORM
        $laundry = Laundry::create($cid, $color_id, LaundryStatus::ACTIVE);

        // Report if failed
        if ($laundry == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new laundry object.");
            exit();
        }

        //Generate JSON encoding of new Laundry
        header("Content-type: application/json");
        print($laundry->getJSON());
        exit();
    } else {
        $lid = intval($path_components[1]);
        $laundry = Laundry::findByID($lid);
        $laundry->setStatus($status);

        //Generate JSON encoding of new Laundry
        header("Content-type: application/json");
        print($laundry->getJSON());
        exit();
    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // laundry.php
    if (count($path_components) < 2 || $path_components[1] == "") {
        //Generate JSON encoding of all laundry ids
        header("Content-type: application/json");
        print(json_encode(Laundry::getAllIDs()));
        exit();
    }

    // laundry.php/?
    switch(strtolower($path_components[1])){
        case "active": // laundry.php/active
            // Return all active laundries
            break;
        case "done": // laundry.php/active
            // Return all done laundries
            break;
        case "error": // laundry.php/active
            // Return all events with outstanding errors
            break;
        default: // laundry.php/<id>(/events)?
            // Verify <id> is integer
            $lid = intval($path_components[1]);
            $laundry = Laundry::findByID($lid);
            if ($laundry == null) {
                header("HTTP/1.0 500 Server Error");
                print("Could not find laundry with id:" . $lid);
                exit();
            }

            // laundry.php/<id>
            if (count($path_components) < 3){
                //Generate JSON encoding of new Laundry
                header("Content-type: application/json");
                print($laundry->getJSON());
                exit();
            }

            // laundry.php/<id>/events
            if (strtolower($path_components[2]) != "events") {
                header("HTTP/1.0 400 Server Error");
                print("Invalid url component:" . $path_components[2]);
                exit();
            }

            header("Content-type: application/json");
            print(json_encode(Event::getEventIDsForLaundry($lid)));
            exit();
    }

    //Generate JSON encoding of new Laundry
    header("HTTP/1.0 400 Bad Request");
    print("Did not understand URL:".$_SERVER['REQUEST_METHOD']);
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL:".$_SERVER['REQUEST_METHOD']);
exit();

?>