var customerController = angular.module('customerController', []);

customerController.controller('CustomerCtrl', [
	'$scope',
	'$uibModal',
	'$http',
	'$filter',
	'$route',
	function($scope, $uibModal, $http, $filter, $route){
		$scope.gender_values = [
			{
				val: 'm',
				name: 'Male'
			},
			{
				val: 'f',
				name: 'Female'
			}
		];

		$scope.customers = [];
		$scope.customers_flag = true;

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.opened = {};

		$scope.FormatDate = function(date){
			return $filter('date')(date, 'yyyy-MM-dd');
		};

		$scope.open = function($event, elementOpened){
			$event.preventDefault();
			$event.stopPropagation();

			$scope.opened[elementOpened] = !$scope.opened[elementOpened];
		};

		$scope.GetCustomers = function(){
			var request = $.post('customers/GetCustomers');
			request.done(
				function(response){
					if(response.data){
						$scope.customers = response.data;
						$scope.customers_flag = false;
					}
					else
						$scope.customers_flag = true;

					$scope.$apply();
					return;
				}
			);
		};

		$scope.GetCustomers();

		$scope.DeleteCustomer = function(index){
			var request = $.post('customers/DeleteCustomer', {customers_id: $scope.customers[index].customers_id});
			request.done(
				function(response){
					if(response.result){
						$scope.customers.splice(index, 1);
						if($scope.customers.length==0)
							$scope.customers_flag = true;
						$scope.$apply();
						return;
					}

					$scope.error_flag = true;
					$scope.errors = response.messages;
					$scope.$apply();
					return;
				}
			);
		}

		$scope.OpenNewCustomerModal = function(size){
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'NewCustomerModalContent.html',
				controller: 'NewCustomerCtrl',
				size: size
			});

			modalInstance.result.then(
				function(response){
					if(response=="ok")
						$scope.GetCustomers();
				},
				function(response){
					console.log(response);
				}
			);
		}

		$scope.UpdateCustomer = function(table_column, data, customers_id, index){
			var customers_data = {
				customers_id: customers_id,
				table_column: table_column,
				data: data
			};
			if(table_column=='customers_gender'){
				customers_data.data = data.val;
				$scope.customers[index].customers_gender = data.val;
			}
			else if(table_column=='customers_birthdate')
				customers_data.data = $filter('date')(data, 'yyyy-MM-dd');

			$.post('customers/UpdateCustomer', customers_data);
			$scope.GetCustomers();
			return;
		}
	}
]);

customerController.controller('NewCustomerCtrl', [
	'$scope',
	'$uibModalInstance',
	'$http',
	function($scope, $uibModalInstance, $http){
		$scope.gender_values = [
			{
				val: '',
				name: ''
			},
			{
				val: 'm',
				name: 'Male'
			},
			{
				val: 'f',
				name: 'Female'
			}
		];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.customer_credentials = {};
		$scope.ok = function(){
			$scope.customer_credentials.customers_firstname = $scope.new_customer_firstname;
			$scope.customer_credentials.customers_middleinitial = $scope.new_customer_middleinitial;
			$scope.customer_credentials.customers_lastname = $scope.new_customer_lastname;
			$scope.customer_credentials.customers_nickname = $scope.new_customer_nickname;
			$scope.customer_credentials.customers_birthdate = $scope.new_customer_birthdate;
			$scope.customer_credentials.customers_contact_number = $scope.new_customer_contactnum;

			var request = $.post('customers/AddCustomer', $scope.customer_credentials);
			request.done(
				function(response){
					if(response.result){
						$uibModalInstance.close('ok');
						return;
					}

					$scope.error_flag = true;
					$scope.errors = response.messages;
					$scope.$apply();
					return;
				}
			);
		};

		$scope.cancel = function(){
			$uibModalInstance.dismiss('cancel');
		}

		$scope.changedGender = function(value){
			$scope.customer_credentials.customers_gender = value.val;
		}
	}
])