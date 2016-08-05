var customerController = angular.module('loanController', []);

customerController.controller('LoanCtrl', [
	'$scope',
	'$uibModal',
	'$http',
	'$filter',
	function($scope, $uibModal, $http, $filter){
		$scope.loans = [];
		$scope.loans_flag = false;

		$scope.customers = [];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.GetCustomers = function(){
			var request = $.post('customers/GetCustomers');
			request.done(
				function(response){
					if(response.data)
						$scope.customers = response.data;

					$scope.$apply();
					return;
				}
			);
		}
		$scope.GetCustomers();

		$scope.GetLoans = function(){
			var request = $.post('loans/GetLoans');
			request.done(
				function(response){
					if(response.data){
						$scope.loans = response.data;
						$scope.loans_flag = false;

						$scope.total_loan_amount = 0;
						$scope.total_payed_amount = 0;
						angular.forEach($scope.loans, function(value, key){
							$scope.total_loan_amount += parseFloat(value.loans_amount);
							$scope.total_payed_amount += value.total_payed_amount;
						});
					}
					else
						$scope.loans_flag = true;

					$scope.$apply();
					return;
				}
			);
		};
		$scope.GetLoans();

		$scope.SearchWithDate = function(){
			var request = $.post('loans/GetLoans/1', {start_date: $scope.start_date, end_date: $scope.end_date});
			request.done(
				function(response){
					if(response.data){
						$scope.loans = response.data;
						$scope.loans_flag = false;
					}
					else{
						$scope.loans = [];
						$scope.loans_flag = true;
					}

					$scope.$apply();
					return;
				}
			);
		};

		$scope.DeleteLoan = function(index){
			var request = $.post('loans/DeleteLoan', {loans_id: $scope.loans[index].loans_id});
			request.done(
				function(response){
					if(response.result){
						$scope.loans.splice(index, 1);
						if($scope.loans.length==0)
							$scope.loans_flag = true;
					}
					else{
						$scope.error_flag = true;
						$scope.errors = response.messages;
					}

					$scope.$apply();
					return;
				}
			);
		};

		$scope.UpdateLoan = function(table_column, data, loans_id, index){
			var loan_data = {
				loans_id: loans_id,
				table_column: table_column,
				data: data
			};
			if(table_column=='customers_id'){
				loan_data.data = data.customers_id;
				$scope.loans[index].customers_id = data.customers_id;
				$scope.loans[index].customers_name = data.customers_name;
			}
			else if(table_column=='loans_date')
				loan_data.data = $filter('date')(data, 'yyyy-MM-dd');

			$.post('loans/UpdateLoan', loan_data);
			$scope.GetLoans();
			return;
		}

		$scope.OpenNewLoanModal = function(size){
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'NewLoanModalContent.html',
				controller: 'NewLoanCtrl',
				size: size
			});

			modalInstance.result.then(
				function(response){
					if(response=="ok")
						$scope.GetLoans();
				},
				function(response){
					console.log(response);
				}
			);
		};

		$scope.ComputeFilteredData = function(loans, customer_to_search){
			$scope.total_payed_amount = 0;
			$scope.total_loan_amount = 0;
			var filtered_data = $filter('filter')(loans, customer_to_search);
			angular.forEach(filtered_data, function(value, key){
				$scope.total_loan_amount += parseFloat(value.loans_amount);
				$scope.total_payed_amount += value.total_payed_amount;
			});
		}

		$scope.opened = {};
		$scope.open = function($event, elementOpened){
			$event.preventDefault();
			$event.stopPropagation();

			$scope.opened[elementOpened] = !$scope.opened[elementOpened];
		};
	}
]);

customerController.controller('NewLoanCtrl', [
	'$scope',
	'$uibModalInstance',
	'$http',
	function($scope, $uibModalInstance, $http){
		$scope.customers = [];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.loan_credentials = {};		
		$scope.ok = function(){
			$scope.loan_credentials.loans_title = $scope.new_loan_title;
			$scope.loan_credentials.loans_date = $scope.new_loan_date;
			$scope.loan_credentials.loans_amount = $scope.new_loan_amount;
			$scope.loan_credentials.loans_interest = $scope.new_loan_interest;

			var request = $.post('loans/AddLoan', $scope.loan_credentials);
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

		$scope.GetCustomers = function(){
			var request = $.post('customers/GetCustomers');
			request.done(
				function(response){
					if(response.data)
						$scope.customers = response.data;

					$scope.$apply();
					return;
				}
			);
		}
		$scope.GetCustomers();

		$scope.cancel = function(){
			$uibModalInstance.dismiss('cancel');
		};

		$scope.ChangedCustomer = function(value){
			$scope.loan_credentials.customers_id = value.customers_id;
		}
	}
]);