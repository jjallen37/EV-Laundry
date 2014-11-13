<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Color.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /laundry.php/<id>
    // Modifies existing laundry data probably
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        $result = Color::getColors();
        if (!empty($result)){
            //Generate JSON encoding of new Review
            header("Content-type: application/json");
            print($result);
            exit();
        }
    }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>