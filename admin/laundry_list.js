/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";
$(document).on('pagecreate',function() {

    // Fetch the employee list and async
    var text = "";
    $.ajax(url_base + "php/laundry.php/",
        {type: "GET",
            async:false,
            dataType: "json",
            success: function(lids, status, jqXHR) {
                if (lids===null) {
                    text = document.createTextNode("No Laundry in Database.");
                } else {
                    // Add each employee async
                    for (var i=0; i<lids.length; i++) {
                        addLaundry(lids[i]);
                    }
                }
            },
            error: function(jqXHR, status, error) {
                text = document.createTextNode("Error fetching laundry ids:"+jqXHR.responseText);
            }});
    $('#laundry_status').html(text);
});

function addLaundry(lid) {
    $.ajax(url_base + "php/laundry.php/" + lid, {
        type: "GET",
        dataType: "json",
        success: function (employee_json, status, jqXHR) {
            console.log("Display Laundry: " + lid);
            var l = new Laundry(employee_json);
            $('#laundries_ul').append(l.makeCompactLi());
            $('#laundries_ul').listview('refresh');

        },
        error: function(jqXHR, status, error) {
            console.log("Lid:"+lid+"....Response"+jqXHR.responseText);
        }
    });
}
