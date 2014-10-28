/**
 * login.js
 */

$(document).ready(function() {

    $("input[type=submit]").click(function(e) {
        // similar behavior as clicking on a link
        console.log("login submit");
        window.location.href = "sorting.html";
        e.preventDefault();
    });
});

