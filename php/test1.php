<?php

// Set default timezone
date_default_timezone_set('UTC');

try {
    /**************************************
     * Create databases and                *
     * open connections                    *
     **************************************/

    // Create (connect to) SQLite database in file
    $db = new PDO('sqlite:../db/test.db');
    // Set errormode to exceptions
    $db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);

    /**************************************
     * Remove Old Tables                   *
     **************************************/
    $db->exec("DROP TABLE IF EXISTS Laundry");
    $db->exec("DROP TABLE IF EXISTS Color");

    /**************************************
     * Create New Tables                   *
     **************************************/
    $db->exec("CREATE TABLE IF NOT EXISTS Laundry (
                lid INTEGER PRIMARY KEY,
                cid INTEGER,
                color INTEGER,
                isDone INTEGER)");
    $db->exec("CREATE TABLE IF NOT EXISTS Color (
                colorID INTEGER PRIMARY KEY,
                colorName TEXT,
                hex TEXT)");

    /**************************************
     * Insert colors            *
     **************************************/


    // Employee test data
    $colors = array('Red','Green','Blue','White', 'Black');
    $hexes = array('#ff0000','#00ff00','#0000ff','#ffffff','#000000');

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO Color (colorName, hex)
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

    print("All good here");
    exit();
} catch(PDOException $e) {
    // Print PDOException message
    header("HTTP/1.0 500 Internal Server Error");
    print("Clothes not found");
    exit();
}
?>
