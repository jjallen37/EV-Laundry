<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');
require_once('Customer.php');
require_once('Event.php');

abstract class LaundryStatus
{
    const ACTIVE = 'ACTIVE';
    const DONE = 'DONE';
    const ERROR = 'ERROR';
}

class Laundry
{
    private $lid;
    private $cid;
    private $color_id;
    private $status;

    public static function create($cid, $color_id, $status) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Laundry (cid, color_id, status)
                    VALUES (:cid, :color_id, :status)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':cid', $cid);
        $stmt->bindParam(':color_id', $color_id);
        $stmt->bindParam(':status', $status);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Laundry($new_id, $cid, $color_id, $status);
        }
        return null;
    }

    private function __construct($lid, $cid, $color_id, $status) {
        $this->lid = $lid;
        $this->cid = $cid;
        $this->color_id = $color_id;
        $this->status = $status;
    }

    public static function findByID($lid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Laundry WHERE lid == :lid');
        $stmt->bindParam(':lid', $lid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $laundry = new Laundry($row['lid'],
                                $row['cid'],
                                $row['color_id'],
                                $row['status']);
            return $laundry;
        }
        return null;
    }

    public static function getAllIDs(){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT lid FROM Laundry');
//                              ORDER BY startTime');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['lid']);
        }
        return $id_array;
    }

    public function getID() {
        return $this->lid;
    }
    public function getCID() {
        return $this->cid;
    }
    public function getColorID() {
        return $this->color_id;
    }
    public function getStatus() {
        return $this->status;
    }
    public function setStatus() {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $update = "UPDATE Laundry
                    SET status = :status
                    WHERE lid = :lid";
        $stmt = $db->prepare($update);
        // Bind parameters to statement variables
        $stmt->bindParam(':lid', $this->lid);
        $stmt->bindParam(':status', $this->status);
        // Execute statement
        $stmt->execute();
    }
    public function getCustomer() {
        return Customer::findByID($this->cid);
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['lid'] = $this->lid;
        $json_rep['cid'] = $this->cid;
        $json_rep['color_id'] = $this->color_id;
        $json_rep['status'] = $this->status;
        return json_encode($json_rep, JSON_NUMERIC_CHECK);
    }
}
