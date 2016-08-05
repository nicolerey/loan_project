var app = angular.module('app', [
	'ngRoute',
	'ui.bootstrap',
	'xeditable',
	'frameController',
	'customerController',
	'loanController',
	'loanTransactionController',
	'bankController',
	'bankTransactionController'
]);

app.run(function(editableOptions){
	editableOptions.theme = 'bs3';
});

app.directive('datePicker', function(){
	return {
		restrict: 'A',
		link: function(scope, element, attrs){
			$(element).datepicker({
				format: 'yyyy-mm-dd'
			});
		}
	};
});

app.directive('pFormat', function(){
	return {
		restrict: 'A',
		link: function(scope, element, attrs){			
			$(element).priceFormat({prefix:''});
		}
	};
});

app.filter('capitalize', function() {
  return function(input, scope) {
    output_string = "";
    if(input!=null && input!=""){
		input = input.toLowerCase().split(" ");
	    for(var x=0; x<input.length; x++){
	    	if(input[x].indexOf("null")==-1)
	    		output_string += input[x].substring(0,1).toUpperCase() + input[x].substring(1) + " ";
	    }
	}
	else
		output_string = "-";
    return output_string;
  }
});

app.filter('convert_gender', function() {
  return function(input, scope) {
  	if(input=="m")
    	return "Male";
    else if(input=="f")
    	return "Female";
    else
    	return "-";

    return "";
  }
});

app.config([
	'$routeProvider',
	'$locationProvider',
	function($routeProvider, $locationProvider){
		$routeProvider.
		when('/home', {
			templateUrl: 'assets/views/bank_transaction_list.html',
			controller: 'BankTransactionCtrl'
		}).
		when('/customers', {
			templateUrl: 'assets/views/customer_list.html',
			controller: 'CustomerCtrl'
		}).
		when('/loans', {
			templateUrl: 'assets/views/loan_list.html',
			controller: 'LoanCtrl'
		}).
		when('/loan_transactions', {
			templateUrl: 'assets/views/loan_transaction_list.html',
			controller: 'LoanTransactionCtrl'
		}).
		when('/banks', {
			templateUrl: 'assets/views/bank_list.html',
			controller: 'BankCtrl'
		}).
		when('/bank_transactions', {
			templateUrl: 'assets/views/bank_transaction_list.html',
			controller: 'BankTransactionCtrl'
		}).
		otherwise({
			redirectTo: '/home'
		});

		$locationProvider.html5Mode(true);
	}
]);