/**
 * Created by jjallen on 10/23/14.
 */

$(document).ready(function() {

    $(".washer").click(function(e) {
        console.log("washer clicked : "+e.target.id)
        e.preventDefault();
    });
});
