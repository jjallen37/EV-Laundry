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
    <script src="js/employee_view.js"></script>
    <!-- Header -->
    <div data-role="header">
        <h1>Ev Employee Page</h1>
    </div><!-- /header -->

    <!-- Main Page Content -->
    <div class="ui-content" role="main">
    </div>
</div>
</body>
</html>