<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Sort.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /sort.php/<id>
    // Modifies existing laundry data probably
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        // POST to /sort.php/ to create new sort in db
        // Validate values
        if (!isset($_REQUEST['lid'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing employee id");
            exit();
        }
        $lid = $_REQUEST['lid'];
        if (!isset($_REQUEST['eid'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing employee id");
            exit();
        }
        $eid = $_REQUEST['eid'];
        if (!isset($_REQUEST['tops'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing tops count");
            exit();
        }
        $tops = $_REQUEST['tops'];
        if (!isset($_REQUEST['bottoms'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing bottoms count");
            exit();
        }
        $bottoms = $_REQUEST['bottoms'];
        if (!isset($_REQUEST['socks'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing socks count");
            exit();
        }
        $socks = $_REQUEST['socks'];
        if (!isset($_REQUEST['other'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing other count");
            exit();
        }
        $other = $_REQUEST['other'];

        // Create new Review via ORM
        $sort = Sort::create($lid,$eid,$tops,$bottoms,$socks,$other);

        // Report if failed
        if ($sort == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new sort object.");
            exit();
        }

        //Generate JSON encoding of new Review
        header("Content-type: application/json");
        print($sort->getJSON());
        exit();

    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET sort/<sid>, returns json for sort
    if (count($path_components) >= 2 && $path_components[1] != "") {
        $sid = intval($path_components[1]);
        if ($sid < 1) {
            header("HTTP/1.0 400 Bad Request");
            print("Bad Sort ID");
            exit();
        }

        $sort = Sort::findByID($sid);
        if ($sort == null) {
            header("HTTP/1.0 500 Server Error");
            print("Could not find Sort object");
            exit();
        }

        //Generate JSON encoding of new Sort
        header("Content-type: application/json");
        print($sort->getJSON());
        exit();
    }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>