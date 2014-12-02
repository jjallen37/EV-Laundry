<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Employee
{
    private $eid;
    private $name;

    public static function create($name) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Employees (name)
                    VALUES (:name)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':name', $name);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Employee($new_id, $name);
        }
        return null;
    }

    private function __construct($eid, $name) {
        $this->eid = $eid;
        $this->name = $name;
    }

    public static function getAllIDs() {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT eid FROM Employees');
        $id_array = array();

        foreach ($result as $r){
            $id_array[] = intval($r['eid']);
        }

        return $id_array;
    }

    public static function findByID($eid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Employees WHERE eid == :eid');
        $stmt->bindParam(':eid', $eid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $customer = new Employee($row['eid'],
                $row['name']);
            return $customer;
        }
        return null;
    }


    public function getID() {
        return $this->eid;
    }

    public function getName() {
        return $this->name;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['eid'] = $this->eid;
        $json_rep['name'] = $this->name;
        return json_encode($json_rep, JSON_NUMERIC_CHECK);
    }}
