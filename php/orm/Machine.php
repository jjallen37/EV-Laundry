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
    private $mid;
    private $isDryer;
    private $isLoad;
    private $lid;
    private $eid;
    private $num;
    private $thyme;

    public static function create($isDryer, $isLoad, $lid, $eid, $num) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $thyme = date('Y-m-d H:i:s');
        $insert = "INSERT INTO Machine (isDryer, isLoad, lid, eid, num, thyme)
                    VALUES (:isDryer, :isLoad, :lid, :eid, :num, :thyme)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':isDryer', $isDryer);
        $stmt->bindParam(':isLoad', $isLoad);
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':eid', $eid);
        $stmt->bindParam(':num', $num);
        $stmt->bindParam(':thyme', $thyme);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Machine($isDryer, $isLoad, $lid, $eid, $num, $thyme);
        }
        return null;
    }

    private function __construct($isDryer, $isLoad, $lid, $eid, $num, $thyme) {
        $this->isDryer = $isDryer;
        $this->isLoad = $isLoad;
        $this->lid = $lid;
        $this->eid = $eid;
        $this->num = $num;
        $this->thyme = $thyme;
    }

    /*
     * Input: Washer or Dryer number
     * Output: PHP object representing most recent operation on machine
     */
    public static function getWasher($num){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Machine
                              WHERE isDryer == 0 AND num == :num
                              ORDER BY datetime(thyme) DESC LIMIT 1');
        $stmt->bindParam(':num', $num);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $machine = new Machine($row['isDryer'],
                $row['isLoad'],
                $row['lid'],
                $row['eid'],
                $row['num'],
                $row['thyme']);
            return $machine;
        } else {
            $machine = new Machine(0, 0, 0, 0, $num, 0);
            return $machine;
        }
    }

    public static function getDryer($num){ // Returns last operation on dryer
        return null;
    }

    public function isDryer() {
        return $this->isDryer;
    }
    public function isLoad() {
        return $this->isLoad;
    }
    public function getLID() {
        return $this->lid;
    }
    public function getEID() {
        return $this->eid;
    }
    public function getNum() {
        return $this->num;
    }
    public function getThyme() {
        return $this->thyme;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['isDryer'] = $this->isDryer;
        $json_rep['isLoad'] = $this->isLoad;
        $json_rep['lid'] = $this->lid;
        $json_rep['eid'] = $this->eid;
        $json_rep['num'] = $this->num;
        $json_rep['thyme'] = $this->thyme;
        return json_encode($json_rep, JSON_NUMERIC_CHECK);
    }
}
