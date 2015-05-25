app.factory('Customer', function() {
    var customer = {}
    function set(data) {
        customer = data;
    }
    
    function get() {
        return customer;
    }

    return {
        set: set,
        get: get
    }
    

});