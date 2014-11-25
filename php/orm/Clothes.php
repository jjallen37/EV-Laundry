<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Clothes
{
    private $lid;
    private $isFold;
    private $eid;
    private $tops;
    private $bottoms;
    private $hang_tops;
    private $hang_bottoms;
    private $socks;
    private $towels;
    private $thyme;

    public static function create($lid, $isFold, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $thyme = date('Y-m-d H:i:s');
        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Clothes (lid, isFold, eid, tops, bottoms, socks, other, hang_tops, hang_bottoms, thyme)
                    VALUES (:lid, :isFold, :eid, :tops, :bottoms, :socks, :other, :hang_tops, :hang_bottoms, :thyme)";
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
        $stmt->bindParam(':thyme', $thyme);

        // Execute statement
        if ($stmt->execute()){
            return new Clothes($lid, $isFold, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms, $thyme);
        } else {
            return null;
        }

    }

    private function __construct($lid, $isFold, $eid, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms, $thyme) {
        $this->lid = $lid;
        $this->isFold = $isFold;
        $this->eid = $eid;
        $this->tops = $tops;
        $this->bottoms = $bottoms;
        $this->socks = $socks;
        $this->other = $other;
        $this->hang_tops = $hang_tops;
        $this->hang_bottoms = $hang_bottoms;
        $this->thyme = $thyme;
    }

    public static function findByID_Fold($lid, $isFold) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Clothes WHERE lid == :lid AND isFold == :isFold');
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':isFold', $isFold);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $fold = new Clothes($row['lid'],
                $row['isFold'],
                $row['eid'],
                $row['tops'],
                $row['bottoms'],
                $row['socks'],
                $row['other'],
                $row['hang_tops'],
                $row['hang_bottoms'],
                $row['thyme']);
            return $fold;
        }
        return null;
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
    public function getThyme() {
        return $this->thyme;
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
        $json_rep['thyme'] = $this->thyme;
        return json_encode($json_rep);
    }
}
