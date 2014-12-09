/**
 * Created by jjallen on 12/2/14.
 */

var Employee = function(employee_json){
    this.eid = employee_json.eid;
    this.name = employee_json.name;
};

Employee.prototype.makeCompactLi = function() {
    return $('<li/>', {    //here appending `<li>`
        'id': 'employee-li'+this.eid
    }).append($('<a/>', {    //here appending `<a>` into `<li>`
        'href': 'employee_view.php?eid='+this.eid,
        'data-transition': 'slide',
        'text': this.name
    }));
};

Employee.prototype.makeHeader = function() {
    return $('<h1>'+this.name+'</h1>')
};
