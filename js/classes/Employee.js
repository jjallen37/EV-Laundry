/**
 * Created by jjallen on 12/2/14.
 */

var Employee = function(employee_json){
    this.eid = employee_json.eid;
    this.name = employee_json.name;
};


Employee.prototype.makeCompactLi = function() {
    var href = '/employee_view.php?eid='+this.eid;
    var text = document.createTextNode(this.name);
    var li = $('<li><a></a></li>');
    li.prop('href',href);
    li.html(text);
    return li;
};

