'use strict';
/**
* Description
*  	 Transfer page Controller
*/

app.controller('transferController', function($scope, $http, getTerraCotta){

	//Var initialization
	$scope.newTxTable = [];
	$scope.listCount = 0;
	var newObj = {};
	var newEntry = {};
	$scope.alertNow = false;
	$scope.alertMsg = "";

	//Temp initialization
	$scope.skuMove1 = [];
	$scope.skuMove2 = [];
	$scope.currentPage = 1;
	$scope.itemsPerPage = 20;
	var newObj2 = {};
	var newEntry2 = {};

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

	//When click select...
	$scope.select = function (data) {

		//Add products in new transfer table
		$scope.newTxTable.push({
			'sku':data.sku,
			'title':data.title,
			'model':data.model,
			'variant':data.variant,
			'supplier':data.supplier,
			'salesPrice':data.salesPrice,
			'store1':data.store1,
			'store2':data.store2,
			'store3':data.store3,
			'txQty':0,
			'listCount':$scope.listCount
		});

		$scope.listCount ++;

	}

	//When click >>
	$scope.plusQty = function (data) {

		switch($scope.store) {
		case 'Moema':
			if ($scope.newTxTable[data.listCount].store1 > 0) {
				$scope.newTxTable[data.listCount].txQty ++;
				$scope.newTxTable[data.listCount].store1 --;
			 	break;
			}
			else {
			 	break;
			}
		case 'Jardins':
			if ($scope.newTxTable[data.listCount].store2 > 0) {
				$scope.newTxTable[data.listCount].txQty ++;
				$scope.newTxTable[data.listCount].store2 --;
				break;
			}
			else {
				break;
			}
		case 'Morumbi':
			if ($scope.newTxTable[data.listCount].store4 > 0) {
				$scope.newTxTable[data.listCount].txQty ++;
				$scope.newTxTable[data.listCount].store4 --;
				break;
			}
			else {
				break;
			}
		case 'Museu':
			if ($scope.newTxTable[data.listCount].store3 > 0) {
				$scope.newTxTable[data.listCount].txQty ++;
				$scope.newTxTable[data.listCount].store3 --;
				break;
			}
			else {
				break;
			}
		}

	}

	//When click <<
	$scope.lessQty = function (data) {

		switch($scope.store) {
		case 'Moema':
			if ($scope.newTxTable[data.listCount].txQty > 0) {
				$scope.newTxTable[data.listCount].txQty --;
				$scope.newTxTable[data.listCount].store1 ++;
			 	break;
			}
			else {
			 	break;
			}
		case 'Jardins':
			if ($scope.newTxTable[data.listCount].txQty > 0) {
				$scope.newTxTable[data.listCount].txQty --;
				$scope.newTxTable[data.listCount].store2 ++;
				break;
			}
			else {
				break;
			}
		case 'Morumbi':
			if ($scope.newTxTable[data.listCount].txQty > 0) {
				$scope.newTxTable[data.listCount].txQty --;
				$scope.newTxTable[data.listCount].store4 ++;
				break;
			}
			else {
				break;
			}
		case 'Museu':
			if ($scope.newTxTable[data.listCount].txQty > 0) {
				$scope.newTxTable[data.listCount].txQty --;
				$scope.newTxTable[data.listCount].store3 ++;
				break;
			}
			else {
				break;
			}
		}

	}

	//When click "Enviar Transferencia"
	$scope.sendTx = function () {

		var destStore = angular.element(document.getElementsByName('destStore')[0]).val();

		//Check if store and products are selected
		if (destStore != '? undefined:undefined ?' && $scope.newTxTable.length !== 0) {

			// IMPORTANTE... To add data, origin and destination
			newObj.data = $scope.newTxTable;
			newObj.store = $scope.store;
			newObj.destStore = destStore;

			newEntry = JSON.stringify (newObj);
	
			$http.post('../stock/updt_stock', newEntry).then(function successCallback(response) {
	
				console.log("Envio bem sucedido. Transferencia efetuada!");
				alert(response.data);
				location.reload();

			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

		else {
			console.log("Por favor, selecionar a loja ou produtos!");
			$scope.alertNow = true;
			$scope.alertMsg = "Por favor, selecionar a loja ou produtos!";
		}

	}

	getProducts();

	///////////////////////////////
	// DELETE "Cadastro Inicial" //
	///////////////////////////////

	// Get Suppliers from Database
	getTerraCotta.suppliers().then(function successCallback(response) {
			$scope.suppliers = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// Function to get NFEs from Database
	function getTemp() {

		$http({
			url: '../stock/get_temp',
			method: 'GET',
		}).then(function successCallback(response) {
			for (var i = response.data.length - 1; i >= 0; i--) {
				$scope.skuMove1[response.data[i].sku] = 0;
				$scope.skuMove2[response.data[i].sku] = 0;
			}
			$scope.tempStock = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	// To send itens to Moema stock
	$scope.toMoema = function (sku,stockQty) {
		if (stockQty - $scope.skuMove1[sku]- $scope.skuMove2[sku] > 0) {
			$scope.skuMove1[sku] ++;
		}
	}

	// To send itens to Jardins stock
	$scope.toJardins = function (sku,stockQty) {
		if (stockQty - $scope.skuMove1[sku] - $scope.skuMove2[sku] > 0) {
			$scope.skuMove2[sku] ++;
		}
	}

	// To send itens
	$scope.sendSku = function (sku,stockQty) {
		if (stockQty - $scope.skuMove1[sku]- $scope.skuMove2[sku] == 0) {

			// IMPORTANTE... To add data, origin and destination
			newObj2.sku = sku;
			newObj2.moema = $scope.skuMove1[sku];
			newObj2.jardins = $scope.skuMove2[sku];

			newEntry2 = JSON.stringify (newObj2);
	
			$http.post('../stock/set_stock', newEntry2).then(function successCallback(response) {
				console.log("Envio bem sucedido. Transferencia efetuada!");

				getTemp();

//				console.log(response.data);
//				alert(response.data);
//				location.reload();

			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}
	}

	// To clear list
	$scope.clearSku = function (sku) {
		$scope.skuMove1[sku] = 0;
		$scope.skuMove2[sku] = 0;
	}

	getTemp();

});