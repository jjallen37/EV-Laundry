<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Counts.php');
require_once('orm/Color.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /counts.php/
    // Creates new count
    if (count($path_components) < 2 || $path_components[1] == "") {
        // POST to /counts.php/ to create new count in db
        // Validate values
        if (!isset($_REQUEST['tops'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing bottoms count");
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
        if (!isset($_REQUEST['hang_tops'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing hang_tops count");
            exit();
        }
        $hang_tops = $_REQUEST['hang_tops'];
        if (!isset($_REQUEST['hang_bottoms'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing hang_bottoms count");
            exit();
        }
        $hang_bottoms = $_REQUEST['hang_bottoms'];

        // Create new Review via ORM
        $counts = Counts::create($tops,$bottoms,$socks,$other,$hang_tops,$hang_bottoms);
        // Report if failed
        if ($counts == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new count object.");
            exit();
        }

        //Generate JSON encoding of new Count
        header("Content-type: application/json");
        print($counts->getJSON());
        exit();

    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET /counts.php/
    // Return all count ids
    if (count($path_components) < 2 || $path_components[1] == "") {
        //Generate JSON encoding of all laundry ids
        header("Content-type: application/json");
        print(json_encode(Laundry::getAllIDs()));
        exit();
    }

    // GET /counts.php/<id>
    // Return json for count with id equal to <id>
    $count_id = intval($path_components[1]);
    $count = Counts::findByID($count_id);
    //Generate JSON encoding of new Laundry
    header("Content-type: application/json");
    print($count->getJSON());
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>
