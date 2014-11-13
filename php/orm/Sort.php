<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Sort
{
    private $sid;
    private $lid;
    private $eid;
    private $tops;
    private $bottoms;
    private $socks;
    private $towels;

    public static function create($lid, $eid, $tops, $bottoms, $socks, $other) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Sort (lid, eid, tops, bottoms, socks, other)
                    VALUES (:lid, :eid, :tops, :bottoms, :socks, :other)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':eid', $eid);
        $stmt->bindParam(':tops', $tops);
        $stmt->bindParam(':bottoms', $bottoms);
        $stmt->bindParam(':socks', $socks);
        $stmt->bindParam(':other', $other);

        // Execute statement
        $stmt->execute();
        $sid = $db->lastInsertId();

        if ($sid > 0) {
            return new Sort($sid, $lid, $eid, $tops, $bottoms, $socks, $other);
        }
        return null;
    }

    private function __construct($sid, $lid, $eid, $tops, $bottoms, $socks, $other) {
        $this->sid = $sid;
        $this->lid = $lid;
        $this->eid = $eid;
        $this->tops = $tops;
        $this->bottoms = $bottoms;
        $this->socks = $socks;
        $this->other = $other;
    }

    public static function findByID($sid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Sort WHERE sid == :sid');
        $stmt->bindParam(':sid', $sid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $sort = new Sort($row['sid'],
                $row['lid'],
                $row['eid'],
                $row['tops'],
                $row['bottoms'],
                $row['socks'],
                $row['other']);
            return $sort;
        }
        return null;
    }

    public static function getAllIDs(){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT sid FROM Sort');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['sid']);
        }
        return $id_array;
    }

    public function getID() {
        return $this->sid;
    }
    public function getLID() {
        return $this->lid;
    }
    public function getEID() {
        return $this->eid;
    }
    public function getTops() {
        return $this->tops;
    }
    public function getBottoms() {
        return $this->bottoms;
    }
    public function getSocks() {
        return $this->socks;
    }
    public function getOther() {
        return $this->other;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['sid'] = $this->sid;
        $json_rep['lid'] = $this->lid;
        $json_rep['eid'] = $this->eid;
        $json_rep['tops'] = $this->tops;
        $json_rep['bottoms'] = $this->bottoms;
        $json_rep['socks'] = $this->socks;
        $json_rep['other'] = $this->other;
        return json_encode($json_rep);
    }
}
