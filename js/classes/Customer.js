/**
 * Created by jjallen on 11/20/14.
 */

var Customer = function(customer_json){
    this.cid = customer_json.cid;
    this.name = customer_json.name;
};

Customer.prototype.makeCompactLi = function() {
    return $('<li/>', {    //here appending `<li>`
        'id': 'customer-li'+this.cid
    }).append($('<a/>', {    //here appending `<a>` into `<li>`
        'href': 'customer_view.php?cid='+this.cid,
        'data-transition': 'slide',
        'text': this.name
    }));
};
