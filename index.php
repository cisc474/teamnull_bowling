<!DOCTYPE html>
<html lang="en" ng-app="myApp">

<head>
  <title>Seaford Bowling Lanes BRMS</title>
  <script src="js/angular.min.js"></script>
  <script src="js/angular-route.min.js"></script>
  <script src="js/angular-animate.min.js" ></script>
  <script src="js/toaster.js"></script>
  <script src="app/app.js"></script>
  <script src="app/data.js"></script>
  <script src="app/customer.js"></script>
  <script src="app/authCtrl.js"></script>
  <script src="app/navCtrl.js"></script>
  <script src="app/dashCtrl.js"></script>
  <script src="app/custCtrl.js"></script>
  <script src="app/recCtrl.js"></script>
  <link href="css/toaster.css" rel="stylesheet">
  <link rel="stylesheet" href="/css/index.css" type="text/css" />
</head>

<body ng-cloak="" ng-controller="navCtrl">
    <div class="navbar navbar-default navbar-fixed-top" id="navbar" role="navigation">
      <div class="container">
        <div class="row">
          <div class="navbar-brand" id="brms">Ball Record Mgmt System</div>
           <div class="navbar-header navbar-right">
            <a class="navbar-brand" rel="home" title="Logout" ng-click="logout();">Logout</a>
           </div>
           <div class="navbar-header navbar-right" id="dash">
            <a class="navbar-brand" rel="home" title="Dashboard" ng-show="checkName()"; ng-click="dashboard();">Dashboard</a>
           </div>
          <div class="navbar-brand navbar-right" id="welcome" ng-show="checkName();">Welcome {{name}}!</div>
        </div>
      </div>
    </div>
  <div class="container" style="margin-top:20px;">

    <div data-ng-view="" id="ng-view" class="slide-animation"></div>

  </div>
  <toaster-container class="navbar-brand" style="color:rgb(160, 160, 160);" toaster-options="{'time-out': 1}"></toaster-container>
</body>


</html>