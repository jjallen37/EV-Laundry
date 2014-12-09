<?php

// Set default timezone
date_default_timezone_set('UTC');

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
    $db->exec("DROP TABLE IF EXISTS Clothes");
    $db->exec("DROP TABLE IF EXISTS Machine");
    $db->exec("DROP TABLE IF EXISTS Color");

    /**************************************
     * Create New Tables                   *
     **************************************/
    $db->exec("CREATE TABLE IF NOT EXISTS Customers (
                cid INTEGER PRIMARY KEY,
                name TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Employees (
                eid INTEGER PRIMARY KEY,
                name TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Laundry (
                lid INTEGER PRIMARY KEY,
                cid INTEGER,
                color INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS Color (
                colorID INTEGER PRIMARY KEY,
                color TEXT,
                lid INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS Clothes (
                lid INTEGER NOT NULL,
                eid INTEGER,
                isFold INTEGER NOT NULL,
                tops INTEGER,
                hang_tops INTEGER,
                bottoms INTEGER,
                hang_bottoms INTEGER,
                socks INTEGER,
                other INTEGER,
                thyme TIMESTAMP,
                PRIMARY KEY ( lid, isFold))");
    $db->exec("CREATE TABLE IF NOT EXISTS Machine (
                mid INTEGER PRIMARY KEY,
                lid INTEGER,
                eid INTEGER,
                isDryer INTEGER,
                isLoad INTEGER,
                num INTEGER,
                thyme TIMESTAMP)");

    /**************************************
     * Insert Available colors            *
     **************************************/

    // Employee test data
    $colors = array('Red','Blue','Green','Orange');

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO Color (color, lid)
                VALUES (:color, 0)";
    $stmt = $db->prepare($insert);
    $stmt->bindParam(':color', $color);

    // Add each of the colors
    foreach ($colors as $c) {
        $color = $c;
        $stmt->execute();
    }

    /**************************************
     * Close db connections                *
     **************************************/
    // Close file db connection
    $db = null;

    print("All good here");
    exit();
} catch(PDOException $e) {
    // Print PDOException message
    header("HTTP/1.0 500 Internal Server Error");
    print("Clothes not found");
    exit();
}
?>
