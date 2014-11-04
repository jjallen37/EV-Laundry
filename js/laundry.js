/**
 * Created by jjallen on 10/23/14.
 */

$(document).ready(function() {

    var LaundryState = {
        EMPTY: "empty",
        BUSY: "busy",
        DONE: "done"
    };

    var washers = [LaundryState.EMPTY,
                    LaundryState.EMPTY,
                    LaundryState.EMPTY,
                    LaundryState.EMPTY]

    var dryers = [LaundryState.EMPTY,
        LaundryState.EMPTY,
        LaundryState.EMPTY,
        LaundryState.EMPTY,
        LaundryState.EMPTY,
        LaundryState.EMPTY]

    $(".washer").click(function(e) {
        console.log("washer clicked : "+e.target.id)
        //e.preventDefault();
    });


    $('#empty-washer-submit-btn').click(function(e){

        alert('close dialog?');
        //e.preventDefault();
    });

});
