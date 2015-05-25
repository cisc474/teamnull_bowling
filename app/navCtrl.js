app.controller('navCtrl', function ($scope, $location, $http, Data) {
    $scope.name= "";
    $scope.$on('$routeChangeStart', function(next, current) { 
        Data.get('session').then(function (results) {
            if (results.name) {
                $scope.name = results.name;
            } else {
                $scope.name = "";
            }
        });
    });
    $scope.checkName = function() {
        if($scope.name){
            return true;
        } else {
            return false;
        }
    };
    $scope.dashboard = function () {
        $location.path('dashboard');
    }
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    };
});