var customerController = angular.module('bankController', []);

customerController.controller('BankCtrl', [
	'$scope',
	'$uibModal',
	'$http',
	'$filter',
	function($scope, $uibModal, $http, $filter){
		$scope.banks = [];
		$scope.banks_flag = false;

		$scope.error_flag = false;
		$scope.errors = [];

		$scope.GetBanks = function(){
			var request = $.post('banks/GetBanks');
			request.done(
				function(response){
					if(response.data){
						$scope.banks = response.data;
						$scope.banks_flag = false;
					}
					else
						$scope.banks_flag = true;

					$scope.$apply();
					return;
				}
			);
		}
		$scope.GetBanks();

		$scope.DeleteBank = function(index){
			var request = $.post('banks/DeleteBank', {banks_id: $scope.banks[index].banks_id});
			request.done(
				function(response){
					if(response.result){
						$scope.banks.splice(index, 1);
						if($scope.banks.length==0)
							$scope.banks_flag = true;
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

		$scope.UpdateBank = function(table_column, data, banks_id, index){
			var bank_data = {
				banks_id: banks_id,
				table_column: table_column,
				data: data
			};

			var request = $.post('banks/UpdateBank', bank_data);
			request.done(
				function(response){
					if(!response.result){
						$scope.error_flag = true;
						$scope.errors = response.messages;
					}

					return;
				}
			);

			$scope.GetBanks();
		}

		$scope.OpenNewBankModal = function(size){
			var modalInstance = $uibModal.open({
				animation: true,
				templateUrl: 'NewBankModalContent.html',
				controller: 'NewBankCtrl',
				size: size
			});

			modalInstance.result.then(
				function(response){
					if(response=="ok")
						$scope.GetBanks();
				},
				function(response){
					console.log(response);
				}
			);
		};
	}
]);

customerController.controller('NewBankCtrl', [
	'$scope',
	'$uibModalInstance',
	'$http',
	function($scope, $uibModalInstance, $http){
		$scope.error_flag = false;
		$scope.errors = [];

		$scope.bank_credentials = {};		
		$scope.ok = function(){
			$scope.bank_credentials.banks_name = $scope.new_bank_name;
			$scope.bank_credentials.banks_balance = $scope.new_bank_balance;

			var request = $.post('banks/AddBank', $scope.bank_credentials);
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
	}
]);