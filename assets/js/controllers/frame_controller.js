var frameController = angular.module('frameController', []);

frameController.controller('TitleCtrl', [
	'$scope',
	'$http',
	'$window',
	function($scope, $http, $window){
		$scope.tab_title = "Home";
		$scope.LogoutUser = function(){
			var request = $.post('logout');
			request.done(function(response){
				if(response.result)
					$window.location.href = 'login';
			});
		}
	}
]);