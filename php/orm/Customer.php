<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Customer
{
    private $cid;
    private $name;

    public static function create($name) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Customers (name)
                    VALUES (:name)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':name', $name);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Customer($new_id, $name);
        }
        return null;
    }

    private function __construct($cid, $name) {
        $this->cid = $cid;
        $this->name = $name;
    }

    public static function findByID($cid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Customers WHERE cid == :cid');
        $stmt->bindParam(':cid', $cid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $customer = new Customer($row['cid'],
                $row['name']);
            return $customer;
        }
        return null;
    }

    public static function getAllIDs(){
        return Customer::getIDQuery('SELECT cid FROM Customer
                                      ORDER BY name');
    }

    public static function getActiveIDs(){
        return Customer::getIDQuery('SELECT C.cid FROM Color C, Laundry L
                                     WHERE L.cid = C.cid
                                     AND L.status = "ACTIVE"');
    }
    public static function getFreeIDs(){
        return Customer::getIDQuery("SELECT C.cid FROM Customers C
                                      LEFT JOIN Laundry L
                                      ON L.cid = C.cid
                                      AND L.status = 'ACTIVE'
                                      WHERE L.cid IS NULL");
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
            $id_array[] = intval($r['cid']);
        }
        return $id_array;
    }

    public function getID() {
        return $this->cid;
    }
    public function getName() {
        return $this->name;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['cid'] = $this->cid;
        $json_rep['name'] = $this->name;
        return json_encode($json_rep, JSON_NUMERIC_CHECK);
    }}
