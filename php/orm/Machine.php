<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Machine
{
    private $isDryer;
    private $isLoad;
    private $lid;
    private $eid;
    private $timestamp;

    public static function create($isDryer, $isLoad, $num, $lid, $eid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Clothes (lid, isFold, eid, tops, bottoms, socks, other, hang_tops, hang_bottoms)
                    VALUES (:lid, :isFold, :eid, :tops, :bottoms, :socks, :other, :hang_tops, :hang_bottoms)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':isFold', $isFold);
        $stmt->bindParam(':eid', $eid);
        $stmt->bindParam(':tops', $tops);
        $stmt->bindParam(':bottoms', $bottoms);
        $stmt->bindParam(':socks', $socks);
        $stmt->bindParam(':other', $other);
        $stmt->bindParam(':hang_tops', $hang_tops);
        $stmt->bindParam(':hang_bottoms', $hang_bottoms);

        // Execute statement
        if ($stmt->execute()){
            return new Clothes($lid, $isFold, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms);
        } else {
            return null;
        }
    }

    private function __construct($lid, $isFold, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        $this->lid = $lid;
        $this->isFold = $isFold;
        $this->eid = $eid;
        $this->tops = $tops;
        $this->bottoms = $bottoms;
        $this->socks = $socks;
        $this->other = $other;
        $this->hang_tops = $hang_tops;
        $this->hang_bottoms = $hang_bottoms;
    }

    public static function getWasher($num){
        return null;
    }
    public static function getDryer($num){ // Returns last operation on dryer
        return null;
    }

    public static function getMachinesForLID($lid){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT  FROM Machine');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['fid']);
        }
        return $id_array;
    }

    /*
     * Will I really use this?
     */
    public static function getAllIDs(){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT fid FROM Clothes');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['fid']);
        }
        return $id_array;
    }

    public function getLID() {
        return $this->lid;
    }
    public function isFold() {
        return $this->isFold;
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
    public function getHangTops() {
        return $this->hang_tops;
    }
    public function getHangBottoms() {
        return $this->hang_bottoms;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['lid'] = $this->lid;
        $json_rep['isFold'] = $this->isFold;
        $json_rep['eid'] = $this->eid;
        $json_rep['tops'] = $this->tops;
        $json_rep['bottoms'] = $this->bottoms;
        $json_rep['socks'] = $this->socks;
        $json_rep['other'] = $this->other;
        $json_rep['hang_tops'] = $this->hang_tops;
        $json_rep['hang_bottoms'] = $this->hang_bottoms;
        return json_encode($json_rep);
    }
}
