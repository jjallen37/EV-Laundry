<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Laundry.php');
require_once('orm/Color.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /laundry.php/<id>
    // Modifies existing laundry data probably
    if (count($path_components) >= 2 && $path_components[1] != "") {
           header("HTTP/1.0 404 Not Found");
           print("We do not support laundry.php/<id> posts right now");
           exit();
    } else {
        // POST to /laundry.php/ to create new laundry
        // Validate values
        if (!isset($_REQUEST['cid'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing customer id");
            exit();
        }
        $cid = $_REQUEST['cid'];

        if (!isset($_REQUEST['color'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing color");
            exit();
        }
        $color = intval($_REQUEST['color']);

        $c_lid = intval(Color::getLID($color));
        if ($c_lid > 0){
            header("HTTP/1.0 400 Server Error");
            print("Invalid color id:" + $c_lid);
            exit();
        }

        // Create new Review via ORM
        $laundry = Laundry::create($cid, $color);

        // Report if failed
        if ($laundry == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new laundry object.");
            exit();
        }

        // Lock color until folding
        Color::setColor($color, $laundry->getID());

        //Generate JSON encoding of new Review
        header("Content-type: application/json");
        print($laundry->getJSON());
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