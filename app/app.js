var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster']);

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl'
        })
        .when('/signup', {
            title: 'Signup',
            templateUrl: 'partials/signup.html',
            controller: 'authCtrl'
        })
        .when('/dashboard', {
            title: 'Dashboard',
            templateUrl: 'partials/dashboard.html',
            controller: 'dashCtrl'
        })
        .when('/customer', {
            title: 'Customer',
            templateUrl: 'partials/existingcustomer.html',
            controller: 'custCtrl'
        })
        .when('/', {
            title: 'Login',
            templateUrl: 'partials/login.html',
            controller: 'authCtrl',
            role: '0'
        })
        .when('/newrecord',{
            title: 'NewRecord',
            templateUrl: 'partials/newrecord.html',
            controller: 'recCtrl'
        })
        .otherwise({
            redirectTo: '/login'
        });
}])
.run(function ($rootScope, $location, Data) {
    $rootScope.$on("$routeChangeStart", function (event, next, current) {
        $rootScope.authenticated = false;
        Data.get('session').then(function (results) {
            if (results.name) {
                $rootScope.authenticated = true;
                $rootScope.name = results.name;
            } else {
                var nextUrl = next.$$route.originalPath;
                if (nextUrl == '/signup' || nextUrl == '/login') {

                } else {
                    $location.path("/login");
                }
            }
        });
    });
});