<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Refresh Test Data for EV Laundry</title>
</head>
<body>

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
    echo("Previous Tables Dropped<br>");

    /**************************************
     * Create New Tables                   *
     **************************************/
    $db->exec("CREATE TABLE IF NOT EXISTS Customers (
                cid INTEGER PRIMARY KEY,
                firstName TEXT,
                lastName TEXT)");
    $db->exec("CREATE TABLE IF NOT EXISTS Employees (
                eid INTEGER PRIMARY KEY,
                firstName TEXT,
                lastName TEXT)");
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
    echo("Tables Created<br>");

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
     * Insert Employee Data                *
     **************************************/
    // Employee test data
    $employees = array(
        array('firstName' => 'Earl',
            'lastName' => 'Extraordinary'),
        array('firstName' => 'Vince',
            'lastName' => 'Ventures'),
        array('firstName' => 'Employee',
            'lastName' => 'Third')
    );

    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO Employees (firstName, lastName)
                VALUES (:firstName, :lastName)";
    $stmt = $db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);

    // Loop thru all messages and execute prepared insert statement
    foreach ($employees as $e) {
        // Set values to bound variables
        $firstName = $e['firstName'];
        $lastName = $e['lastName'];

        // Execute statement
        $stmt->execute();
    }
    echo("Employees Inserted into Database<br>");

    /**************************************
     * Insert Customer Data               *
     **************************************/
    // Array with some test data to insert to database
    $customers = array(
        array('firstName' => 'Gary',
            'lastName' => 'Granville'),
        array('firstName' => 'Sally',
            'lastName' => 'Sorority'),
        array('firstName' => 'Customer',
            'lastName' => 'Thrice')
    );


    // Prepare INSERT statement to SQLite3 file db
    $insert = "INSERT INTO Customers (firstName, lastName)
                VALUES (:firstName, :lastName)";
    $stmt = $db->prepare($insert);

    // Bind parameters to statement variables
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':lastName', $lastName);

    // Loop thru all messages and execute prepared insert statement
    foreach ($customers as $c) {
        // Set values to bound variables
        $firstName = $c['firstName'];
        $lastName = $c['lastName'];

        // Execute statement
        $stmt->execute();
    }
    echo("Customers Inserted into Database<br>");

    /**************************************
     * Close db connections                *
     **************************************/

    // Close file db connection
    $db = null;
} catch(PDOException $e) {
    // Print PDOException message
    echo "Error working with databases";
    echo $e->getMessage();
}
?>


</body>
</html>