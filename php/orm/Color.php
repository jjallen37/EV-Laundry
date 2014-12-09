<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 *
 * Active Colors
 * SELECT C.color_id
    FROM Color C, Laundry L
    WHERE C.color_id = L.color_id
    AND L.status = 'ACTIVE'
 *
 * Inactive Colors
 *  SELECT C.color_id
    FROM Color C
    LEFT JOIN Laundry L
    ON C.color_id = L.color_id AND L.status = 'ACTIVE'
    WHERE L.color_id IS NULL
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
    private $hex;

    public static function create($color_id, $color, $hex) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Color (color_id, color, hex)
                    VALUES (:color_id, :color, :hex)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':color_id', $color_id);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':hex', $hex);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Color($color_id, $color, $hex);
        }
        return null;
    }

    private function __construct($color_id, $color, $hex) {
        $this->color_id = $color_id;
        $this->color = $color;
        $this->hex = $hex;
    }

    public static function findByID($color_id) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Color WHERE color_id == :color_id');
        $stmt->bindParam(':color_id', $color_id);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $color = new Color(intval($row['color_id']),
                $row['color'],
                $row['hex']);
            return $color;
        }
        return null;
    }

    public static function getLID($color_id){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Execute query for ids
        $stmt = $db->prepare('SELECT L.lid FROM Laundry L
                              WHERE L.color_id = :color_id AND
                               L.status = "ACTIVE"
                               LIMIT 1');
        $stmt->bindParam(':color_id', $color_id);
        $stmt->execute();
        $result = $stmt->fetch();

        // No active laundry with color_id
        if (!$result){
            return -1;
        }

        return $result['lid'];
    }

    public static function getAllIDs(){
        return Color::getIDQuery('SELECT color_id FROM Color');
    }

    public static function getActiveIDs(){
        return Color::getIDQuery('SELECT C.color_id FROM Color C, Laundry L
                                  WHERE L.color_id = C.color_id AND
                                        L.status = "ACTIVE"');
    }
    public static function getFreeIDs(){
        return Color::getIDQuery("SELECT C.color_id FROM Color C
                                  LEFT JOIN Laundry L
                                  ON L.color_id = C.color_id AND
                                            L.status = 'ACTIVE'
                                  WHERE L.color_id IS NULL");
    }

    private static function getIDQuery($query){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // Execute query for ids
        $result = $db->query($query);
        // Create id array
        $id_array = array();
        foreach ($result as $r) {
            $id_array[] = intval($r['color_id']);
        }
        return $id_array;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['color_id'] = $this->color_id;
        $json_rep['color'] = $this->color;
        $json_rep['hex'] = $this->hex;
        return json_encode($json_rep);
    }
}

