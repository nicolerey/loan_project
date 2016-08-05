var customerController = angular.module('loanTransactionController', []);

customerController.controller('LoanTransactionCtrl', [
	'$scope',
	'$uibModal',
	'$http',
	'$filter',
	'$route',
	function($scope, $uibModal, $http, $filter, $route){
		$scope.loan_transactions = [];
		$scope.loan_transactions_flag = false;

		$scope.customers = [];
		$scope.loans = [];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.GetLoanTransactions = function(){
			var request = $.post('loan_transactions/GetLoanTransactions');
			request.done(
				function(response){
					if(response.data){
						$scope.loan_transactions = response.data;
						$scope.loan_transactions_flag = false;

						$scope.total_payed_amount = 0;
						angular.forEach($scope.loan_transactions, function(value, key){
							$scope.total_payed_amount += parseFloat(value.loan_payments_amount);
						});

						$scope.$apply();
						return;
					}

					$scope.loan_transactions_flag = true;
					$scope.$apply();
					return;
				}
			);
		};
		$scope.GetLoanTransactions();

		$scope.SearchWithDate = function(){
			var request = $.post('loan_transactions/GetLoanTransactions/1', {start_date: $scope.start_date, end_date: $scope.end_date});
			request.done(
				function(response){
					if(response.data){
						$scope.loan_transactions = response.data;
						$scope.loan_transactions_flag = false;
					}
					else{
						$scope.loan_transactions = [];
						$scope.loan_transactions_flag = true;
					}

					$scope.$apply();
					return;
				}
			);
		}

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
					if(response.data)
						$scope.loans = response.data;

					$scope.$apply();
					return;
				}
			);
		};
		$scope.GetLoans();

		$scope.DeleteLoanTransaction = function(index){
			var request = $.post('loan_transactions/DeleteLoanTransaction', {loan_payments_id: $scope.loan_transactions[index].loan_payments_id});
			request.done(
				function(response){
					if(response.result){
						$scope.loan_transactions.splice(index, 1);
						if($scope.loan_transactions.length==0)
							$scope.loan_transactions_flag = true;
						$scope.$apply();
						return;
					}

					$scope.error_flag = true;
					$scope.errors = response.messages;
					$scope.$apply();
					return;
				}
			);
		};

		$scope.UpdateLoanTransaction = function(table_column, data, loans_id, index){
			var loan_payment_data = {
				loan_payments_id: loans_id,
				table_column: table_column,
				data: data
			};
			if(table_column=='customers_id'){
				loan_payment_data.data = data.customers_id;
				$scope.loan_transactions[index].customers_id = data.customers_id;
				$scope.loan_transactions[index].customer_name = data.customers_name;
			}
			else if(table_column=='loan_payments_date')
				loan_payment_data.data = $filter('date')(data, 'yyyy-MM-dd');
			else if(table_column=='loans_id')
				loan_payment_data.data = data.loans_id;

			var request = $.post('loan_transactions/UpdateLoanTransaction', loan_payment_data);
			request.done(
				function(response){
					if(!response.result){
						$scope.error_flag = true;
						$scope.errors = response.messages;
						$scope.$apply();
					}

					return;
				}
			);

			$scope.GetLoanTransactions();
		}

		$scope.OpenNewLoanTransactionModal = function(size){
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'NewLoanTransactionModalContent.html',
				controller: 'NewLoanTransactionCtrl',
				size: size
			});

			modalInstance.result.then(
				function(response){
					if(response=="ok")
						$scope.GetLoanTransactions();
				},
				function(response){
					console.log(response);
				}
			);
		};

		$scope.ComputeFilteredData = function(loan_transactions, search_field){
			var filtered_data = $filter('filter')(loan_transactions, search_field);
			$scope.total_payed_amount = 0;
			angular.forEach(filtered_data, function(value, key){
				$scope.total_payed_amount += parseFloat(value.loan_payments_amount);
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

customerController.controller('NewLoanTransactionCtrl', [
	'$scope',
	'$uibModalInstance',
	'$http',
	function($scope, $uibModalInstance, $http){
		$scope.customers = [];
		$scope.loans = [];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.loan_transaction_credentials = {};		
		$scope.ok = function(){
			$scope.loan_transaction_credentials.loan_payments_date = $scope.new_payment_date;
			$scope.loan_transaction_credentials.loan_payments_amount = $scope.new_payment_amount;
			$scope.loan_transaction_credentials.loan_payments_remarks = $scope.new_payment_remarks;

			var request = $.post('loan_transactions/AddLoanTransactions', $scope.loan_transaction_credentials);
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

		$scope.GetLoans = function(){
			var request = $.post('loans/GetLoans');
			request.done(
				function(response){
					if(response.data)
						$scope.loans = response.data;

					$scope.$apply();
					return;
				}
			);
		}
		$scope.GetLoans();

		$scope.cancel = function(){
			$uibModalInstance.dismiss('cancel');
		};

		$scope.ChangedCustomer = function(value){
			$scope.loan_transaction_credentials.customers_id = value.customers_id;
		}

		$scope.ChangedLoan = function(value){
			$scope.loan_transaction_credentials.loans_id = value.loans_id;
		}
	}
]);