<?php

if (!isset($_GET['eid'])) {
    header("Location: http://wwwx.cs.unc.edu/Courses/comp426-f13/jamesml/site/index.html");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Employee View</title>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
    <!--<link rel="stylesheet" href="themes/Theme6.min.css"/>-->
    <!--<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />-->
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</head>
<body>
<div data-role="page" id="employee_page">
    <script src="../js/classes/Employee.js"></script>
    <script src="employee_view.js"></script>
    <!-- Header -->
    <div data-role="header">
        <!-- Header -->
        <h1 id="employee_header"></h1>
        <a href="employees_view.html" class="ui-btn ui-corner-all ui-shadow ui-icon-left ui-btn-icon-left">Employee List</a>
    </div><!-- /header -->

    <!-- Main Page Content -->
    <div class="ui-content" role="main">
        <form id="eid_form">
            <input type='hidden' id='eid' name='eid' value=<?php echo $_GET["eid"]; ?>>
        </form>
        <br>
        <!--  List every event associated with the employee  -->
        <ul id="employee_ul" data-role="listview" data-inset="true">
        </ul>
    </div>
</div>
</body>
</html>