<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Laundry.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (count($path_components) == 2) {
        echo "Path Component:" . $path_components[1];
        // Bathroom not found.
        header("HTTP/1.0 404 Not Found");
        print("Bathroom id: " . $bath_id . " not found.");
        exit();

//        if ($bathroom == null) {
//            // Bathroom not found.
//            header("HTTP/1.0 404 Not Found");
//            print("Bathroom id: " . $bath_id . " not found.");
//            exit();
//        }
//
//        // /bathrooms.php/<bid>/reviews
//        if (count($path_components)==4) {
//            header("Content-type: application/json");
//            print(json_encode(Review::reviewsByBID($bath_id)));
//            exit();
//        }
//
//        // Normal lookup.
//        // Generate JSON encoding as response
//        header("Content-type: application/json");
//        print($bathroom->getJSON());
//        exit();

    }
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than GET

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>