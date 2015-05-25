app.controller('dashCtrl', function ($scope, $location, $http, Data, Customer) {
    $scope.records = {};

    $scope.search = function (customer) {
        Data.post('search', {
            customer: customer
        }).then(function (results) {
            if (results.status == "success") {
                $scope.records = results.records;
            }
            else{
                Data.toast(results);
            }
        });
    };

    $scope.visitCustomer = function (record) {
        Customer.set(record);
        $location.path('customer');
    }

    $scope.newCustomer = function (customer) {
        Data.post('newCustomer', {
            customer: customer,
        }).then(function (results) {
            if (results.status == "success") {
                Data.toast(results);
                customer.customerID = results.customerID;
                Customer.set(customer);
                $location.path('customer');
            }
            else{
                Data.toast(results);
            }
        });
    };
});