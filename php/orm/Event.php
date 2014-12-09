<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:20 AM
 */

ini_set('display_errors', 'On');

/*
 * Describes the values for Event.action
 */
abstract class EventAction
{
    const Sort = 1;
    const Wash_Load = 2;
    const Wash_Unload = 3;
    const Dry_Load = 4;
    const Dry_Unload = 5;
    const Fold = 6;
}

class Event
{
    private $event_id;
    private $lid;
    private $eid;
    private $id;
    private $event_action;
    private $timestamp;

    public static function create($lid,$eid,$id,$event_action,$timestamp) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        // Prepare INSERT statement to SQLite3 file db
        $timestamp = date('Y-m-d H:i:s');
        $insert = "INSERT INTO Events (lid,eid,id,event_action,timestamp)
                    VALUES (:lid,:eid,:id,:event_action,:timestamp)";
        $stmt = $db->prepare($insert);
        // Bind parameters to statement variables
        $stmt->bindParam(':lid', $lid);
        $stmt->bindParam(':eid', $eid);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':event_action', $event_action);
        $stmt->bindParam(':timestamp', $timestamp);

        // Execute statement
        $stmt->execute();
        $new_id = $db->lastInsertId();

        if ($new_id > 0) {
            return new Event($new_id, $lid, $eid, $id, $event_action, $timestamp);
        }
        return null;
    }

    private function __construct($event_id,$lid,$eid,$id,$event_action,$timestamp) {
        $this->event_id = $event_id;
        $this->lid = $lid;
        $this->eid = $eid;
        $this->id = $id;
        $this->event_action = $event_action;
        $this->timestamp = $timestamp;
    }

    public static function findByID($event_id) {
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        // Set errormode to exceptions
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);

        $stmt = $db->prepare('SELECT * FROM Events WHERE event_id = :event_id');
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!empty($row)){
            $laundry = new Event($row['event_id'],
                $row['lid'],
                $row['eid'],
                $row['id'],
                $row['event_action'],
                $row['timestamp']);
            return $laundry;
        }
        return null;
    }

    public static function getAllIDs(){
        return Event::getIDQuery('SELECT event_id FROM Events
                                  ORDER BY timestamp', array());
    }

    public static function getEventIDsForLaundry($lid){
        return Event::getIDQuery('SELECT event_id FROM Events
                                  WHERE lid = ?
                                  ORDER BY timestamp', array($lid));
    }

    private static function getIDQuery($query, $params){
        // Create (connect to) SQLite database in file
        $db = new PDO('sqlite:../db/ev_db.db');
        $db->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION);
        // Execute query for ids
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        // Create id array
        $id_array = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $id_array[] = intval($row['event_id']);
        }
        return $id_array;
    }
    /*
     * Input: Washer or Dryer number
     * Output: PHP object representing most recent operation on machine
     */
    public static function getMachine($lid, $id){
//        // Create (connect to) SQLite database in file
//        $db = new PDO('sqlite:../db/ev_db.db');
//        // Set errormode to exceptions
//        $db->setAttribute(PDO::ATTR_ERRMODE,
//            PDO::ERRMODE_EXCEPTION);
//
//        $stmt = $db->prepare('SELECT * FROM Machine
//                              WHERE lid == :lid AND num == :num
//                              ORDER BY datetime(timestamp) DESC LIMIT 1');
//        $stmt->bindParam(':lid', $lid);
//        $stmt->bindParam(':num', $num);
//        $stmt->execute();
//
//        $row = $stmt->fetch();
//        if (!empty($row)){
//            $machine = new Machine($row['lid'],
//                $row['isLoad'],
//                $row['lid'],
//                $row['eid'],
//                $row['num'],
//                $row['timestamp']);
//            return $machine;
//        } else {
//            $machine = new Machine($lid, 0, 0, 0, $num, 0);
//            return $machine;
//        }
    }

    public function getEventID() {
        return $this->event_id;
    }
    public function getLID() {
        return $this->lid;
    }
    public function getEID() {
        return $this->eid;
    }
    public function getID() {
        return $this->id;
    }
    public function getEventAction() {
        return $this->event_action;
    }
    public function getTimestamp() {
        return $this->timestamp;
    }

    public function getJSON() {
        $json_rep = array();
        $json_rep['event_id'] = $this->event_id;
        $json_rep['lid'] = $this->lid;
        $json_rep['eid'] = $this->eid;
        $json_rep['id'] = $this->id;
        $json_rep['event_action'] = $this->event_action;
        $json_rep['timestamp'] = $this->timestamp;
        return json_encode($json_rep);
    }
}
