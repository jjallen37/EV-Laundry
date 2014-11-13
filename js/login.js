/**
 * login.js
 */

$(document).ready(function() {
    $("input[type=submit]").click(function(e) {
        e.preventDefault();
        // similar behavior as clicking on a link
        var user = $("#select-employee").val();
        var eid = $("#select-employee").prop('selectedIndex') + 1;

        if(typeof(Storage) !== "undefined") {
            sessionStorage.username = user;
            sessionStorage.eid = eid;
        } else {
            alert("Sorry, your browser does not support web storage...");
        }

        var task = $("#select-task").prop('selectedIndex');
        switch (task){
            case 0:
                window.location.href = "sorting.html";
                break;
            case 1:
                window.location.href = "laundry.html";
                break;
            case 2:
                window.location.href = "folding.html";
                break;
        }
    });
});

