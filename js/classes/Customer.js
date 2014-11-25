/**
 * Created by jjallen on 11/20/14.
 */

var Customer = function(customer_json){
    this.cid = customer_json.cid;
    this.firstName = customer_json.firstName;
    this.lastName = customer_json.lastName;
    this.name = customer_json.name;
};
