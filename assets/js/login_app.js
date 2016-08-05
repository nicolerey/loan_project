var loginApp = angular.module('loginApp', []);

var controller = {};
controller.loginController = function($scope, $http, $window){
	$scope.errors = [];
	$scope.login_error = false;

	$scope.LoginAttempt = function(){
		var login_credentials = {users_name: $scope.username, users_password: $scope.password};

		var request = $.post($scope.url + "login/LoginAttempt", login_credentials);
		request.done(function(response){
			if(!response.result){
				$scope.errors = response.messages;
				$scope.login_error = true;

				$scope.$apply();
				return;
			}

			$window.location.href = ($scope.url + "home");
			return;
		});
	};
};

loginApp.controller(controller);