/**
 * Created by jjallen on 12/2/14.
 */

var Color = function(color_json){
    this.color_id = color_json.color_id;
    this.color = color_json.color;
    this.hex = color_json.hex;
};

Color.prototype.makeSelectOption = function() {
    return '<option value="'+this.color_id+'">'+this.color+'</option>';
};

function findColorByID(id){
    console.log("find color");
    var url_base = "php";
    var color = null;
    $.ajax(url_base + "/color.php/" + id,
        {
            type: "GET",
            async: false,
            success: function(color_json, status, jqXHR) {
                color = new Color(color_json);
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching available color_id:"+id+
                            "..."+jqXHR.responseText);
            }});
    return color;
};

function findLidFromColorID(color_id){
    var url_base = "php";
    var lid = -1;
    $.ajax(url_base + "/color.php/active/" + color_id,
        {
            type: "GET",
            async: false,
            success: function(new_lid, status, jqXHR) {
                lid = new_lid;
            },
            error: function(jqXHR, status, error) {
                console.log("Error fetching lid for color id:"+"..."+jqXHR.responseText);
            }});
    return lid;
}
