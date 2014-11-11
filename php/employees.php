<?php
/**
 * Created by PhpStorm.
 * User: jjallen
 * Date: 11/11/14
 * Time: 10:39 AM
 */

ini_set('display_errors', 'On');
require_once('orm/Employee.php');



//Generate JSON encoding of new Review
//header("Content-type: application/json");
//print($new_review->getJSON());

//echo ("About to create Customer<br>");
// Create new Review via ORM

/*
 * Testing code
 */
//$allIDs = Employee::getAllIDs();
//foreach ($allIDs as $id){
//    $employee = Employee::findByID($id);
//    echo "Customer Name : " . $employee->getFirstName() . "<br>";
//}
//
//$new_employee = Employee::create("EmployeeFirst","EmployeeLast");
//
//$allIDs = Employee::getAllIDs();
//foreach ($allIDs as $id){
//    $employee = Employee::findByID($id);
//    echo "Customer Name : " . $employee->getFirstName() . "<br>";
//}

exit();
?>
