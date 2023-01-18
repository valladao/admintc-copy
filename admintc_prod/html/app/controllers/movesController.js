'use strict';
/**
* Description
*  	 Move page Controller
*/

app.controller('movesController', function($scope, $http){

	// Var initialization
	$scope.activeTab = 0; $scope.disableClose = true; $scope.moveTable = []; 
	var newObj = {}; $scope.move = []; var newEntry = {};

	//Time initialization
	var todayDate = new Date();
	todayDate.setMinutes(todayDate.getMinutes() - todayDate.getTimezoneOffset());
	$scope.move.date = todayDate.toISOString().slice(0,10);

	// To filter sku in search
	$scope.skuFilter = function (item) {
		if (item.sku == $scope.skuInput) {
			return true;
		}
		else {
			return false;
		}
	}

	// Function to get NFEs from Database
	function getProducts() {

		$http({
			url: '../stock/get',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.productTable = response.data;

			//Get store name from hidden input field and use to filter product
			var text = angular.element(document.getElementsByName('store')[0]).val();
			$scope.store = text;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	//When click arrow down...
	$scope.select = function (data) {

		var storeQty = 0;

		switch($scope.store) {
		case 'Moema':
			storeQty = data.store1;
			break;
		case 'Jardins':
			storeQty = data.store2;
			break;
		case 'Morumbi':
			storeQty = data.store4;
			break;
		case 'Museu':
			storeQty = data.store3;
			break;
		}

		if (storeQty >= 0) {

			//Add products in new transfer table
			$scope.moveTable.push({
				'sku':data.sku,
				'title':data.title,
				'model':data.model,
				'variant':data.variant,
				'type':data.type,
				'supplier':data.supplier,
				'unitCost':data.unitCost,
				'idVar':data.idVar,
				'total':Number(storeQty),
				'idInventory':data.idInventory,
				'changeQty': 0
			});

		}

		else {
			console.log("Quantidade ZERO!");
		}
	
	}

	//When click -
	$scope.lessQty = function (moveQty,total,index) {

		if (total + moveQty > 0) {
			$scope.moveTable[index].changeQty --;
		}
		
	}

	//When click +
	$scope.plusQty = function (moveQty,index) {

		$scope.moveTable[index].changeQty ++;
		
	}

	//When you click Proseguir >>
	$scope.closeList = function () {

		// To validate move quantities (no zero move)
		var valid = true;

		for (var i = $scope.moveTable.length - 1; i >= 0; i--) {
			if ($scope.moveTable[i].changeQty == 0) {
				valid = false;
			}
		}

		if (valid) {
			console.log("OK para ir em frente");
			$scope.disableClose = false;
			$scope.activeTab = 1;
		}
		else {
			console.log("Quantidade errada!");
		}

	}

	//When you close and send the stock move to database
	$scope.sendMove = function () {

		if ($scope.move.reason && $scope.move.note) {

			//Get username name from hidden input field
			var username = angular.element(document.getElementsByName('username')[0]).val();
	
			//Calculate move total cost
			var moveTotal = 0;
			for (var i = 0; i < $scope.moveTable.length; i++) {
				var moveItem = $scope.moveTable[i];
				moveTotal += (Number(moveItem.unitCost) * Number(moveItem.changeQty));
			}
	
			newObj.date = $scope.move.date;
			newObj.store = $scope.store;
			newObj.username = username.toLowerCase();
			newObj.reason = $scope.move.reason;
			newObj.note = $scope.move.note;
			newObj.changeSale = $scope.move.changeSale;
			newObj.moveTotal = moveTotal;
			newObj.moveTable = $scope.moveTable;
	
			newEntry = JSON.stringify (newObj);
	
			$http.post('../stock/move_stock', newEntry).then(function successCallback(response) {
		
				console.log("Envio bem sucedido. Transferencia efetuada!");
				alert(response.data);
				location.reload();
	
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// Avoid change qty with no validation after click back in List
	$scope.back2list = function () {
		$scope.disableClose = true;
	}

	getProducts();

});