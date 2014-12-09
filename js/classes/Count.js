/**
 * Created by jjallen on 11/18/14.
 */

var Count = function(count_json){
    this.count_id = count_json.count_id;
    this.tops = count_json.tops;
    this.bottoms = count_json.bottoms;
    this.socks = count_json.socks;
    this.other = count_json.other;
    this.hang_tops = count_json.hang_tops;
    this.hang_bottoms = count_json.hang_bottoms;
};

Count.prototype.equals = function(aCount) {
    if (this.tops != aCount.tops){ return false; }
    if (this.bottoms != aCount.bottoms){ return false; }
    if (this.socks != aCount.socks){ return false; }
    if (this.other != aCount.other){ return false; }
    return true;
};

Count.prototype.makeCompactDiv = function() {
    var clothes_div = $("<div></div>");
    clothes_div.addClass("clothes_div");

    if (this.isFold){
        clothes_div.text("Folding");
    } else {
        clothes_div.text("Sorting");
    }
    clothes_div.text($("<br>"));
    clothes_div.text("LID:"+this.lid);
    clothes_div.text($("<br>"));
    clothes_div.text("EID:"+this.eid);

    return clothes_div;
};

function createCount(tops, bottoms, socks, other, hang_tops, hang_bottoms){
    // Insert request
    var json_str = '{' +
        '"tops"         :' + tops + ',' +
        '"bottoms"      :' + bottoms + ',' +
        '"socks"        :' + socks + ',' +
        '"other"        :' + other + ',' +
        '"hang_tops"    :' + hang_tops + ',' +
        '"hang_bottoms" :' + hang_bottoms + "}";
    var obj = JSON.parse(json_str);
    var count = null;
    $.ajax(url_base + "/counts.php/",
        {type: "POST",
            async: false,
            dataType: "json",
            data: obj,
            success: function(count_json, status, jqXHR) {
                count = new Count(count_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure creating count:"+jqXHR.responseText);
            }});
    return count;
}

function findCountByID(count_id){
    // Insert request
    var count = null;
    $.ajax(url_base + "/counts.php/" + count_id + "/",
        {type: "GET",
            async: false,
            success: function(count_json, status, jqXHR) {
                count = new Count(count_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Failure finding count by id:"+count_id+"..."+jqXHR.responseText);
            }});
    return count;
}
