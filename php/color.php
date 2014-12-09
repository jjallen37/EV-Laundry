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
    // GET color.php/
    // Return all color ids
    if (count($path_components) < 2 || $path_components[1] == "") {
        //Generate JSON encoding of all color ids
        header("Content-type: application/json");
        print(json_encode(Color::getAllIDs()));
        exit();
    }

    // color.php/(active | free | <id>)
    $id_array = array();
    switch(strtolower($path_components[1])) {
        case "active": // color.php/active(/<color_id>)?
            if (count($path_components) < 3 || $path_components[2] == ""){
                $id_array = Color::getActiveIDs();
                break;
            }
            // Get lid
            $color_id = intval($path_components[2]);
            $lid = Color::getLID($color_id);
            header("Content-type: application/json");
            print(json_encode($lid));
            exit();
        case "free": // color.php/free
            // Return all unassigned colors
            $id_array = Color::getFreeIDs();
            break;
        default:  // GET color.php/<id>
            $color_id = intval($path_components[1]);
            $color = Color::findByID($color_id);
            if ($color == null) {
                header("HTTP/1.0 500 Server Error");
                print("Could not find color with id:" . $color_id);
                exit();
            }

            //Generate JSON encoding of new Laundry
            header("Content-type: application/json");
            print($color->getJSON());
            exit();

    }

    header("Content-type: application/json");
    print(json_encode($id_array));
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>