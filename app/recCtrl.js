app.controller('recCtrl', function ($scope, $location, $http, Data, Customer) {
    $scope.customer=Customer.get();
    $scope.newBall = function (ball) {
        Data.post('newball', {
            ball: ball,
            customerID:$scope.customer.customerID
        }).then(function (results){
            $location.path('customer');
            Data.toast(results);
        }
    )};
    $scope.cancel = function() {
        $location.path('customer');
    }
});