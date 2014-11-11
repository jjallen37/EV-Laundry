<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:39 AM
 */

ini_set('display_errors', 'On');
require_once('orm/Customer.php');


// Report if failed
if ($new_review == null) {
//    header("HTTP/1.0 500 Server Error");
//    print("Server couldn't create new review.");
    echo("Server couldn't create new review.<br>");
    exit();
}

//Generate JSON encoding of new Review
//header("Content-type: application/json");
//print($new_review->getJSON());

//echo ("About to create Customer<br>");
//// Create new Review via ORM
//$new_review = Customer::create("James","Allen");
//$allIDs = Customer::getAllIDs();
//foreach ($allIDs as $id){
//    $customer = Customer::findByID($id);
//}

exit();
?>

</body>
</html>
