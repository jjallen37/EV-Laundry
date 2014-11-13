<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Fold
{
    private $fid;
    private $lid;
    private $eid;
    private $tops;
    private $bottoms;
    private $hang_tops;
    private $hang_bottoms;
    private $socks;
    private $towels;

    public static function create($lid, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Fold (lid, eid, tops, bottoms, socks, other, hang_tops, hang_bottoms)
                    VALUES (:lid, :eid, :tops, :bottoms, :socks, :other, :hang_tops, :hang_bottoms)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':eid', $eid);
        $stmt->bindParam(':tops', $tops);
        $stmt->bindParam(':bottoms', $bottoms);
        $stmt->bindParam(':socks', $socks);
        $stmt->bindParam(':other', $other);
        $stmt->bindParam(':hang_tops', $hang_tops);
        $stmt->bindParam(':hang_bottoms', $hang_bottoms);

        // Execute statement
        $stmt->execute();
        $fid = $db->lastInsertId();

        if ($fid > 0) {
            return new Fold($fid, $lid, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms);
        }
        return null;
    }

    private function __construct($fid, $lid, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        $this->fid = $fid;
        $this->lid = $lid;
        $this->eid = $eid;
        $this->tops = $tops;
        $this->bottoms = $bottoms;
        $this->socks = $socks;
        $this->other = $other;
        $this->hang_tops = $hang_tops;
        $this->hang_bottoms = $hang_bottoms;
    }

    public static function findByID($fid) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Fold WHERE fid == :fid');
        $stmt->bindParam(':lid', $fid);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $fold = new Fold($row['fid'],
                $row['lid'],
                $row['eid'],
                $row['tops'],
                $row['bottoms'],
                $row['socks'],
                $row['other'],
                $row['hang_tops'],
                $row['hang_bottoms']);
            return $fold;
        }
        return null;
    }

    public static function getAllIDs(){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $result = $db->query('SELECT fid FROM Fold');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['fid']);
        }
        return $id_array;
    }

    public function getID() {
        return $this->fid;
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
    public function getHangTops() {
        return $this->hang_tops;
    }
    public function getHangBottoms() {
        return $this->hang_bottoms;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['fid'] = $this->fid;
        $json_rep['lid'] = $this->lid;
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
