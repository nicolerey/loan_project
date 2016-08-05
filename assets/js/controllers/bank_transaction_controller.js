var customerController = angular.module('bankTransactionController', []);

customerController.controller('BankTransactionCtrl', [
	'$scope',
	'$uibModal',
	'$http',
	'$filter',
	function($scope, $uibModal, $http, $filter){
		$scope.bank_transactions = [];
		$scope.bank_transactions_flag = false;

		$scope.banks = [];
		$scope.bank_transaction_type = [
			{
				value: 'w',
				name: 'Widthraw'
			},
			{
				value: 'd',
				name: 'Deposit'
			},
			{
				value: 'i',
				name: 'Interest'
			}
		];

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.GetBanks = function(){
			var request = $.post('banks/GetBanks');
			request.done(
				function(response){
					if(response.data)
						$scope.banks = response.data;

					$scope.$apply();
					return;
				}
			);
		};
		$scope.GetBanks();

		$scope.GetBankTransactions = function(){
			var request = $.post('bank_transactions/GetBankTransactions');
			request.done(
				function(response){
					if(response.data){
						$scope.bank_transactions = response.data;
						$scope.bank_transactions_flag = false;

						$scope.total_transaction_amount = 0;
						angular.forEach($scope.bank_transactions, function(value, key){
							$scope.total_transaction_amount += parseFloat(value.bank_transactions_amount);
						});
					}
					else
						$scope.bank_transactions_flag = true;

					$scope.$apply();
					return;
				}
			);
		}
		$scope.GetBankTransactions();

		$scope.SearchWithDate = function(){
			var request = $.post('bank_transactions/GetBankTransactions/1', {start_date: $scope.start_date, end_date: $scope.end_date});
			request.done(
				function(response){
					if(response.data){
						$scope.bank_transactions = response.data;
						$scope.bank_transactions_flag = false;
					}
					else{
						$scope.bank_transactions = [];
						$scope.bank_transactions_flag = true;
					}

					$scope.$apply();
					return;
				}
			);
		}

		$scope.DeleteBankTransaction = function(index){
			var request = $.post('bank_transactions/DeleteBankTransaction', {bank_transactions_id: $scope.bank_transactions[index].bank_transactions_id});
			request.done(
				function(response){
					if(response.result){
						$scope.bank_transactions.splice(index, 1);
						if($scope.bank_transactions.length==0)
							$scope.bank_transactions_flag = true;
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

		$scope.UpdateBankTransaction = function(table_column, data, bank_transactions_id, index){
			var bank_transaction_data = {
				bank_transactions_id: bank_transactions_id,
				table_column: table_column,
				data: data
			};
			if(table_column=="bank_transactions_date")
				bank_transaction_data.data = $filter('date')(data, 'yyyy-MM-dd');
			else if(table_column=="banks_id")
				bank_transaction_data.data = data.banks_id;
			else if(table_column=="bank_transactions_type")
				bank_transaction_data.data = data.value;

			var request = $.post('bank_transactions/UpdateBankTransaction', bank_transaction_data);
			request.done(
				function(response){
					if(!response.result){
						$scope.error_flag = true;
						$scope.errors = response.messages;
					}

					return;
				}
			);

			$scope.GetBankTransactions();
		}

		$scope.OpenNewBankTransactionModal = function(size){
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'NewBankTransactionModalContent.html',
				controller: 'NewBankTransactionCtrl',
				size: size
			});

			modalInstance.result.then(
				function(response){
					if(response=="ok")
						$scope.GetBankTransactions();
				},
				function(response){
					console.log(response);
				}
			);
		};

		$scope.ComputeFilteredData = function(bank_transactions, search_field){
			var filtered_data = $filter('filter')(bank_transactions, search_field);
			$scope.total_transaction_amount = 0;
			angular.forEach(filtered_data, function(value, key){
				$scope.total_transaction_amount += parseFloat(value.bank_transactions_amount);
			});
		}

		$scope.opened = {};
		$scope.open = function($event, elementOpened){
			$event.preventDefault();
			$event.stopPropagation();

			$scope.opened[elementOpened] = !$scope.opened[elementOpened];
		};

		$scope.FormatTransactionType = function(data){
			if(data=="w")
				return "Widthraw";
			else if(data=="d")
				return "Deposit";
			else if(data=="i")
				return "Interest";
			else
				return "";
		}
	}
]);

customerController.controller('NewBankTransactionCtrl', [
	'$scope',
	'$uibModalInstance',
	'$http',
	function($scope, $uibModalInstance, $http){
		$scope.error_flag = false;
		$scope.errors = [];

		$scope.bank_transaction_type = [
			{
				value: 'w',
				name: 'Widthraw'
			},
			{
				value: 'd',
				name: 'Deposit'
			},
			{
				value: 'i',
				name: 'Interest'
			}
		];

		$scope.banks = [];

		$scope.GetBanks = function(){
			var request = $.post('banks/GetBanks');
			request.done(
				function(response){
					if(response.data)
						$scope.banks = response.data;

					$scope.$apply();
					return;
				}
			);
		};
		$scope.GetBanks();

		$scope.bank_transaction_credentials = {};		
		$scope.ok = function(){
			$scope.bank_transaction_credentials.bank_transactions_date = $scope.new_bank_transaction_date;
			$scope.bank_transaction_credentials.bank_transactions_amount = $scope.new_bank_transaction_amount;
			$scope.bank_transaction_credentials.bank_transactions_remarks = $scope.new_bank_transaction_remarks;

			var request = $.post('bank_transactions/AddBankTransaction', $scope.bank_transaction_credentials);
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
		};

		$scope.ChangedTransactionType = function(data){
			$scope.bank_transaction_credentials.bank_transactions_type = data.value;
		}

		$scope.ChangedBank = function(data){
			$scope.bank_transaction_credentials.banks_id = data.banks_id;
		}
	}
]);