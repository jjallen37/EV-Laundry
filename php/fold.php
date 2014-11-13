<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 1:36 PM
 */

ini_set('display_errors', 'On');
require_once('orm/Fold.php');
require_once('orm/Color.php');

$path_components = explode('/', $_SERVER['PATH_INFO']);

// Note that since extra path info starts with '/'
// First element of path_components is always defined and always empty.
// Note that we only retreive bathrooms, never update or add new ones.

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // POST to /fold.php/<id>
    // Modifies existing laundry data probably
    if (!(count($path_components) >= 2 && $path_components[1] != "")) {
        // POST to /fold.php/ to create new fold in db
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
        $fold = Fold::create($lid,$eid,$tops,$bottoms,$socks,$other,$hang_tops,$hang_bottoms);
        // Report if failed
        if ($fold == null) {
            header("HTTP/1.0 500 Server Error");
            print("Server couldn't create new fold object.");
            exit();
        }

        // Release the color
        Color::setColor($colorID, 0);

        //Generate JSON encoding of new Review
        header("Content-type: application/json");
        print($fold->getJSON());
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
