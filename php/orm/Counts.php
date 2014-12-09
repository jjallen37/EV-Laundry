<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

class Counts
{
    private $count_id;
    private $tops;
    private $bottoms;
    private $socks;
    private $other;
    private $hang_tops;
    private $hang_bottoms;

    public static function create($tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $thyme = date('Y-m-d H:i:s');
        // Prepare INSERT statement to SQLite3 file db
        $insert = "INSERT INTO Counts (tops, bottoms, socks, other, hang_tops, hang_bottoms)
                    VALUES (:tops, :bottoms, :socks, :other, :hang_tops, :hang_bottoms)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':tops', $tops);
        $stmt->bindParam(':bottoms', $bottoms);
        $stmt->bindParam(':socks', $socks);
        $stmt->bindParam(':other', $other);
        $stmt->bindParam(':hang_tops', $hang_tops);
        $stmt->bindParam(':hang_bottoms', $hang_bottoms);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Counts($new_id, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms);
        }
        return null;
    }

    private function __construct($count_id, $tops, $bottoms, $socks, $other, $hang_tops, $hang_bottoms) {
        $this->count_id = $count_id;
        $this->tops = $tops;
        $this->bottoms = $bottoms;
        $this->socks = $socks;
        $this->other = $other;
        $this->hang_tops = $hang_tops;
        $this->hang_bottoms = $hang_bottoms;
    }

    public static function findByID($count_id) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Counts
                              WHERE count_id = :count_id');
        $stmt->bindParam(':count_id', $count_id);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $count = new Counts($row['count_id'],
                $row['tops'],
                $row['bottoms'],
                $row['socks'],
                $row['other'],
                $row['hang_tops'],
                $row['hang_bottoms']);
            return $count;
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

        $result = $db->query('SELECT count_id FROM Counts');
        $id_array = array();
        foreach ($result as $r){
            $id_array[] = intval($r['count_id']);
        }
        return $id_array;
    }

    public function getCountID() {
        return $this->count_id;
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
        $json_rep['count_id'] = $this->count_id;
        $json_rep['tops'] = $this->tops;
        $json_rep['bottoms'] = $this->bottoms;
        $json_rep['socks'] = $this->socks;
        $json_rep['other'] = $this->other;
        $json_rep['hang_tops'] = $this->hang_tops;
        $json_rep['hang_bottoms'] = $this->hang_bottoms;
        return json_encode($json_rep);
    }
}
