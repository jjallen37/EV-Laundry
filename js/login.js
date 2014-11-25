/**
 * login.js
 *
 * This controls the logic behind employees switching
 * identities for activities and tasks.
 */

$(document).ready(function() {
    $("input[type=submit]").click(function(e) {
        e.preventDefault();

        //
        var user = $("#select-employee").val();
        var eid = $("#select-employee").prop('selectedIndex') + 1;

        // NOTE: Will hang when used in incognito...in Safari
        if(typeof(Storage) !== "undefined") {
            sessionStorage.username = user;
            sessionStorage.eid = eid;
        } else {
            alert("Sorry, your browser does not support web storage...");
            return;
        }

        // Redirect browser to selected task
        var task = $("#select-task").prop('selectedIndex');
        switch (task){
            case 0:
                window.location.href = "sorting.html";
                break;
            case 1:
                window.location.href = "washing.html";
                break;
            case 2:
                window.location.href = "drying.html";
                break;
            case 3:
                window.location.href = "folding.html";
                break;
            default:
                return;
        }
    });
});

