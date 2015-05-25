app.controller('custCtrl', function ($scope, $location, $http, Data, Customer) {
    $scope.customer=Customer.get();
    $scope.balls=[];
    
    $scope.editCustomer = function (customer) {
        Data.post('editCustomer', {
            customer: $scope.customer
        }).then(function (results) {
            Data.toast(results);
        });
    };
    
    $scope.addBall = function (customer) {
        $location.path('newrecord');
    };
    
    $scope.deleteBall = function(id) {
        console.log($scope.balls);
        Data.post('deleteBall', {
            id: id
        }).then(function(results) {
            if(results.status == "success") {
                console.log("id is: " + id);
                var newBalls = new Array();
                $scope.balls.forEach(function (ball){ 
                    if(ball.ballID != id){
                        newBalls.push(ball);
                        console.log("fun times");
                    }
                });
                $scope.balls = newBalls;
            }
            console.log($scope.balls);
            Data.toast(results);
        });
    };
    
    $scope.$on('$viewContentLoaded', function() {
        Data.post('getBalls', {
            customerID:$scope.customer.customerID
        }).then(function (results) {
            if (results.status == "success") {
                $scope.balls = results.balls;
            }
        });
    });
      
});