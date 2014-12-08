/**
 * Created by jjallen on 12/2/14.
 */

var url_base = "../";
$(document).on('pagecreate',function() {
    var lid = $('#lid').val(); // Saved from php
    var lul = $('#laundry_ul');

    // Load the employee header
    $.ajax(url_base + "php/laundry.php/" + lid, {
        type: "GET",
        dataType: "json",
        async: false,
        success: function (laundry_json, status, jqXHR) {
            var laundry = new Laundry(laundry_json);
            //Populate header
            $('#laundry_header').text(laundry.lid);//TODO header div

            var rowTitles = ["Tops","Bottoms","Socks","Other"];


            lul.listview('refresh');
        },
        error: function(jqXHR, status, error) {
            //TODO error handle
            $('#laundry_header').html("Error loading laundry:"+lid);
        }
    });

    // TODO
    lul.append($('<li>No Laundry Event Listing Yet</li>'));
    lul.listview('refresh');
});

function makeTableRow(title,sort,fold,hang){
    var row = $('<tr></tr>');
    row.append('</th>',{text:title});
    row.append('</td>',{text:sort});
    row.append('</td>',{text:fold});
    row.append('</td>',{text:hang});
}

//TODO
function addLaundryEvent(id) {
}
