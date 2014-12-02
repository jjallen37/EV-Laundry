/**
 *
 * Created by jjallen on 12/1/14.
 */

$(document).on('pagecreate',function() {
    $.ajax({
        type: "GET",
        url: "db/test_customers.csv",
        dataType: "text",
        success: function(data) {processData(data);}
    });
});

//
function processData(allText) {
    var record_num = 1;  // or however many elements there are in each row
    var allTextLines = allText.split(/\r\n|\n/);
    allTextLines.forEach(function(e){
        if (e.trim() != ""){
            // TODO
            console.log("Insert :"+e);

        }
    });
}
