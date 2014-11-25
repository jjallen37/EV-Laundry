/**
 * Created by jjallen on 11/18/14.
 */

var Clothes = function(count_json){
    this.lid = count_json.lid;
    this.eid = count_json.eid;
    this.isFold = count_json.isFold;
    this.tops = count_json.tops;
    this.bottoms = count_json.bottoms;
    this.socks = count_json.socks;
    this.other = count_json.other;
    this.hang_tops = count_json.hang_tops;
    this.hang_bottoms = count_json.hang_bottoms;
};

Clothes.prototype.makeCompactDiv = function() {
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


