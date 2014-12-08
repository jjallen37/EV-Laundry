<?php

if (!isset($_GET['cid'])) {
    header("Location: ../admin.html");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Customer View</title>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
    <!--<link rel="stylesheet" href="themes/Theme6.min.css"/>-->
    <!--<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />-->
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
</head>
<body>
<div data-role="page" id="customer_page">
    <script src="../js/classes/Customer.js"></script>
    <script src="customer_view.js"></script>
    <!-- Header -->
    <div data-role="header">
        <!-- Header -->
        <h1 id="customer_header"></h1>
        <a href="customer_list.html" class="ui-btn ui-corner-all ui-shadow ui-icon-back ui-btn-icon-left">Customer List</a>
    </div><!-- /header -->

    <!-- Main Page Content -->
    <div class="ui-content" role="main">
        <form id="cid_form">
            <input type='hidden' id='cid' name='cid' value=<?php echo $_GET["cid"]; ?>>
        </form>
        <br>
        <!--  List every event associated with the customer  -->
        <ul id="customer_ul" data-role="listview" data-inset="true">
        </ul>
    </div>
</div>
</body>
</html>