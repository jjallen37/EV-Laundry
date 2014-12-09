<?php

if (!isset($_GET['lid'])) {
    header("Location: ../admin.html");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Laundry View</title>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
    <!--<link rel="stylesheet" href="themes/Theme6.min.css"/>-->
    <!--<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />-->
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</head>
<body>
<div data-role="page" id="laundry_page">
    <script src="../js/classes/Employee.js"></script>
    <script src="../js/classes/Customer.js"></script>
    <script src="../js/classes/Count.js"></script>
    <script src="../js/classes/Laundry.js"></script>
    <script src="laundry_view.js"></script>
    <!-- Header -->
    <div data-role="header">
        <!-- Header -->
        <h1>Laundry View</h1>
        <a href="laundry_list.html" data-ajax="false" class="ui-btn ui-corner-all ui-shadow ui-icon-left ui-btn-icon-left">Laundry List</a>
    </div><!-- /header -->

    <!-- Main Page Content -->
    <div class="ui-content" role="main">
        <!-- Store lid info   -->
        <form id="laundry_form">
            <input type='hidden' id='lid' name='lid' value=<?php echo $_GET["lid"]; ?>>
        </form>
        <!--   Contains customer name, color, day   -->
        <div id="laundry_header"></div>
        <!--   Sort/Fold Count Table   -->
        <br>
        <table data-role="table" id="table_count" class="ui-responsive table-stroke">
                 <thead>
                   <tr>
                         <th data-priority="1"></th>
                         <th data-priority="2">Sorting</th>
                         <th data-priority="2">Folding</th>
                         <th data-priority="3">Hanging</th>
                       </tr>
                 </thead>
                 <tbody id="tbody_count">
                 </tbody>
               </table>
        <!--  List every event associated with the Laundry -->
        <ul id="laundry_ul" data-role="listview" data-inset="true">
        </ul>
    </div>
</div>
</body>
</html>