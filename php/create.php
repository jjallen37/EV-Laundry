<?php

// Set default timezone
date_default_timezone_set('UTC');

/*
 * Describes the values for Event.action
 */


try {
    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    // Create (connect to) SQLite database in file
    $db = new PDO('sqlite:../db/ev_db.db');
    // Set errormode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    /**************************************
     * Remove Old Tables                   *
     **************************************/
    $db->exec("DROP TABLE IF EXISTS Customers");
    $db->exec("DROP TABLE IF EXISTS Employees");
    $db->exec("DROP TABLE IF EXISTS Laundry");
    $db->exec("DROP TABLE IF EXISTS Counts");
    $db->exec("DROP TABLE IF EXISTS Events");
    $db->exec("DROP TABLE IF EXISTS Color");

    /**************************************
     * Create New Tables                   *
     **************************************/
    $db->exec("CREATE TABLE IF NOT EXISTS Laundry (
                lid INTEGER PRIMARY KEY,
                cid INTEGER,
                color_id INTEGER,
                status TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Color (
                color_id INTEGER PRIMARY KEY,
                color TEXT,
                hex TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Customers (
                cid INTEGER PRIMARY KEY,
                name TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Employees (
                eid INTEGER PRIMARY KEY,
                name TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Counts (
                count_id INTEGER PRIMARY KEY,
                tops INTEGER,
                hang_tops INTEGER,
                bottoms INTEGER,
                hang_bottoms INTEGER,
                socks INTEGER,
                other INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS Events (
                event_id INTEGER PRIMARY KEY,
                lid INTEGER,
                eid INTEGER,
                id INTEGER ,
                event_action INTEGER,
                timestamp INTEGER)");

    /**************************************
     * Insert Available colors            *
     **************************************/

    // Color default data
    $colors = array('Red','Green','Blue','White', 'Black');
    $hexes = array('#ff0000','#00ff00','#0000ff','#ffffff','#000000');

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO Color (color, hex)
                VALUES (:color, :hex)";
    $stmt = $db->prepare($insert);
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':hex', $hex);

    // Add each of the colors
    for ($i = 0; $i < count($colors); $i++){
        $color = $colors[$i];
        $hex = $hexes[$i];
        $stmt->execute();
    }

    /**************************************
     * Close db connections                *
     **************************************/
    // Close file db connection
    $db = null;
    exit();

} catch(PDOException $e) {
    // Print PDOException message
    header("HTTP/1.0 500 Internal Server Error");
    print("Clothes not found:".$e);
    exit();
}
?>
