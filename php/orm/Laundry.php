<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Laundry
{
    private $lid;
    private $cid;
    private $color;

    public static function create($cid, $color) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Laundry (cid, color)
                    VALUES (:cid, :color)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':cid', $cid);
        $stmt->bindParam(':color', $color);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            echo ("Inserted. LID=" . $new_id . "<br>");
            return new Laundry($new_id, $cid, $color);
        } else {
            echo ("Laundry creation failed");
        }
        return null;
    }

    private function __construct($lid, $cid, $color) {
        $this->lid = $lid;
        $this->cid = $cid;
        $this->color = $color;
    }

    public static function getAllIDs(){
        $mysqli = new mysqli("classroom.cs.unc.edu", "jjallen", "classroomjja", "jjallendb");

        $result = $mysqli->query("SELECT rid FROM Review");
        $id_array = array();

        if($result){
            while($next_row = $result->fetch_array()) {
                $id_array[] = intval($next_row['rid']);
            }
        }
        return $id_array;
    }

    public function getID() {
        return $this->lid;
    }
    public function getCid() {
        return $this->cid;
    }
    public function getColor() {
        return $this->color;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['lid'] = $this->lid;
        $json_rep['cid'] = $this->cid;
        $json_rep['color'] = $this->color;
        return json_encode($json_rep);
    }
}
