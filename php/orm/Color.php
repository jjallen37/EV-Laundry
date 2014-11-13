<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

abstract class Colors
{
    const Red = 1;
    const Blue = 2;
    const Green = 3;
    const Orange = 4;
    const MAX = 4;
}

class Color
{
    private $color_id;
    private $color;
    private $lid;


    public static function setColor($color_id, $lid){
        if ($color_id < 1 || $color_id > Colors::MAX){
            return -1;
        }

        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Update the color
        $stmt = $db->prepare("UPDATE Color
                              SET lid = :lid
                              WHERE colorID = :colorID");
        $stmt->bindParam(':lid',$lid);
        $stmt->bindParam(':colorID',$color_id);
        $stmt->execute();
    }

    public static function getLID($colorID){
        if ($colorID < 1 || $colorID > Colors::MAX){
            return -1;
        }
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');

        // Validate the value of lid
        $stmt = $db->prepare("SELECT * FROM Color WHERE colorID = :colorID");
        $stmt->bindParam(':colorID', $colorID);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['lid'];
    }


    /*
     * Gets the number associated with color text
     */
    public static function getColorID($text){
        switch ($text)
        {
            case "red":
                return 0;
            case "blue":
                return 1;
            case "green":
                return 2;
            case "orange":
                return 3;
            default:
                return -1;
        }
    }

    public static function getColors(){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT * FROM Color');
        $color_array = array();
        $lid_array = array();
        foreach ($result as $r){
            $color_array[] = $r['color'];
            $lid_array[] = $r['lid'];
        }

        $json_rep = array();
        $json_rep['colors'] = $color_array;
        $json_rep['lids'] = $lid_array;

        return json_encode($json_rep);
    }
}

