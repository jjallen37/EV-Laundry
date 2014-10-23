/**
 * login.js
 */

$(document).ready(function() {

    $("input[type=submit]").click(function(e) {
        // similar behavior as clicking on a link
        e.preventDefault();
        window.location.href = "../sorting.html";
    });
});

