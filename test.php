<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>


<?php
    echo "wat<br>";
?>
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