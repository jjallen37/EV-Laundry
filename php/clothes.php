<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Clothes.php');
require_once('orm/Color.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /clothes.php/
    // Creates new count
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        // POST to /clothes.php/ to create new count in db
        // Validate values
        if (!isset($_REQUEST['colorID'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing color ID");
            exit();
        }

        $colorID = intval($_REQUEST['colorID']);
        $lid = intval(Color::getLID($colorID));
        if ($lid < 1){
            header("http/1.0 400 Bad Request");
            print("Could not get valid lid for color:"+$colorID);
            exit();
        }

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
        if (!isset($_REQUEST['isFold'])) {
            header("HTTP/1.0 400 Bad Request");
            print("Missing isFold integer");
            exit();
        }
        $isFold = $_REQUEST['isFold'];

        $hang_tops = 0;
        $hang_bottoms = 0;

        if (intval($isFold) == 1){
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

            // Release the color
            Color::setColor($colorID, 0);
        }

        // Create new Review via ORM
        $clothes = Clothes::create($lid,$isFold,$eid,$tops,$bottoms,$socks,$other,$hang_tops,$hang_bottoms);
        // Report if failed
        if ($clothes == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new count object.");
            exit();
        }

        //Generate JSON encoding of new Count
        header("Content-type: application/json");
        print($clothes->getJSON());
        exit();

    }
} else if ($_SERVER['REQUEST_METHOD'] == "GET") {
    // GET to /clothes.php/
    if (!isset($_REQUEST['lid'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing lid value");
        exit();
    }
    $lid = $_REQUEST['lid'];
    if (!isset($_REQUEST['isFold'])) {
        header("HTTP/1.0 400 Bad Request");
        print("Missing isFold value");
        exit();
    }
    $isFold = $_REQUEST['isFold'];

    $clothes = Clothes::findByID_Fold($lid,$isFold);
    if ($clothes == null) {
        header("HTTP/1.0 400 Bad Request");
        print("Clothes not found");
        exit();
    }

    //Generate JSON encoding of new Review
    header("Content-type: application/json");
    print($clothes->getJSON());
    exit();
}

// If here, none of the above applied and URL could
// not be interpreted with respect to RESTful conventions.
// Request other than POST

header("HTTP/1.0 400 Bad Request");
print("Did not understand URL");
exit();

?>
