<?php

if (!isset($_GET['lid'])) {
    header("Location: localhost/EV/login.html");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundry View</title>
    <link rel="stylesheet" href="css/laundry_view.css"/>
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.css" />
    <link rel="stylesheet" href="themes/Theme6.min.css"/>
    <link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.4/jquery.mobile-1.4.4.min.js"></script>
    <script src="js/classes/Laundry.js"></script>
<!--    <script src="js/laundry_view.js"></script>-->

    <style>
        .noshadow * {
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;
            box-shadow: none !important;
        }
        form.ui-mini .ui-field-contain fieldset.ui-controlgroup legend small {
            color: #666;
        }
    </style>
</head>

<body>

<div data-role="page" id="laundry_view_page">
    <!-- Main Page Content -->
    <div class="ui-content" role="main">
        <div class="ui-grid-b">
            <div class="ui-block-a">
                <div id="color_block"></div>
            </div>
            <div class="ui-block-b">
                <img src="images/tops.png">
            </div>
            <div class="ui-block-c">
                <div id="color_block2"></div>
            </div>
        </div>


        <!-- Laundry Header -->
<!--        <div id="laundry_header" class="ui-bar ui-bar-c">-->
<!--            <div id="customer_color_block"></div>-->
<!--            <h2 id="customer_name_header">James Allen</h2>-->
<!--        </div>-->

        <!-- Clothing Counts -->
<!--        <div id="clothing_counts">-->
<!--        </div>-->

        <!-- Events -->
<!--        <div id="">-->
<!--        </div>-->
    </div><!-- /content -->
</div><!-- /page -->

</body>
</html>