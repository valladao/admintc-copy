'use strict';
/**
* Description
*  	 Input page Controller
*/

app.controller('inputController', function($scope, $http, getTerraCotta){

	//Var initialization
	$scope.products = {}; $scope.variable = {};
	var newEntry; newEntry = {};
	$scope.newProdTable = []; $scope.purchaseList = []; $scope.filteredItems = [];
	$scope.disableVars = true;
	$scope.alertNow = false; $scope.alertNow2 = false; $scope.alertNow3 = false;
	//Apagar
	$scope.infilter = [];

	//Pagination initialization
	$scope.currentPage = 1; $scope.currentPage2 = 1;
	$scope.search = []; $scope.itemsPerPage = 10; $scope.maxSize = 5;

	//Initial tab status
	$scope.disableTabs = true;
	$scope.disableList = true;
	$scope.activeTab = 0;

	// Function to get NFEs from Database
	function getNFEs() {

		$http({
			url: '../stock/get_nfe',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.tableData = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get Products from database
	function getProducts() {

		$http({
			url: '../stock/get',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.productTable = response.data;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get products and drafts from database
	function getAllProds() {

		$http({
			url: '../stock/get_all',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.allProducts = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get Purchase List from Database
	function getPurchaseList(idNfes) {

		$http({
			url: '../stock/nfe_list/' + idNfes,
			method: 'GET',
		}).then(function successCallback(response) {
			//Enable list tab if there is a list (else disable)
			if (typeof response.data !== 'undefined' && response.data.length > 0) {
				$scope.purchaseList = response.data;
				$scope.disableList = false;
			} else { $scope.disableList = true; }
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Pushed when you select the NFE
	$scope.selectNfe = function (idNfes,supplier,total) {

		$scope.alertNow = false;

		$scope.idNfes = idNfes;
		$scope.supplier = supplier;
		$scope.nfeTotal = Number(total);
		console.log('NFE Selecionada: ' + $scope.idNfes + ' - Fornecedor: ' + $scope.supplier + ' - Total: ' + $scope.nfeTotal);
		$scope.disableTabs = false;
		$scope.activeTab = 1;

		$scope.search.supplier = supplier;

		//Load products from desired NFE
		getPurchaseList(idNfes);
	
	}

	// Pushed when you click button "Criar"
	$scope.newProd = function (newForm) {
		
		$scope.alertNow = false;

		if ($scope.products.title && $scope.products.type && $scope.products.model && $scope.products.variant && $scope.products.salesPrice && $scope.products.ncm && newForm.$valid) {

			// IMPORTANTE... To add supplier and idNfes
			$scope.products.supplier = $scope.supplier;
			$scope.products.idNfes = $scope.idNfes;
	
			newEntry = JSON.stringify ($scope.products);
	
			console.log("Enviando dados do produto...");
	
			$http.post('../product/prod_add', newEntry).then(function successCallback(response) {
	
				console.log("Envio bem sucedido. Produto criado!");
	
				//Add products in new products table
				$scope.newProdTable.push({
					'title':$scope.products.title,
					'type':$scope.products.type,
					'model':$scope.products.model,
					'variant':$scope.products.variant,
					'salesPrice':$scope.products.salesPrice,
					'ncm':$scope.products.ncm,
					'barcode':$scope.products.barcode
				});
	
				getPurchaseList($scope.idNfes);
				$scope.alertNow2 = false;
	
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});
				
		}

		else {
			console.log("Falta de informações para cadastrar produto!");
			$scope.alertNow2 = true;
		}

	}

	// To add already exist product in purchase list
	$scope.addProd = function (sku,price) {

		$scope.alertNow = false;

		var entry = {};
		entry['sku'] = sku;
		entry['price'] = price;
		entry['idNfes'] = $scope.idNfes;

		newEntry = JSON.stringify (entry);

		$http.post('../stock/list_add', newEntry).then(function successCallback(response) {
			console.log("Envio bem sucedido. Produto adicionado!");
			getPurchaseList($scope.idNfes);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	// Sum all subtotals to show total
	$scope.getTotal = function () {
		var total = 0;
		for (var i = 0; i < $scope.purchaseList.length; i++) {
			var product = $scope.purchaseList[i];
			total += (product.unitCost * product.quantity);
		}

		$scope.total = total;
		return total;
	}

	// Save NFE when click the button
	$scope.saveNfe = function () {
		console.log('Salvando nota...');
		//console.log($scope.purchaseList);

		newEntry = JSON.stringify ($scope.purchaseList);

		$http.post('../stock/list_save', newEntry).then(function successCallback(response) {
			console.log("Envio bem sucedido. Lista Salva!");
			$scope.alertNow = true;
			//console.log(response.data);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			$scope.alertNow = false;
		});

	}

	// Get Types from Database
	getTerraCotta.types().then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// Get Models from Database
	getTerraCotta.models().then(function successCallback(response) {
			$scope.models = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// Close NFE when click the button
	$scope.closeNfe = function () {
		console.log('Fechando a nota...');
		if ($scope.total.toFixed(2) == $scope.nfeTotal.toFixed(2)) {
			//Saving nfe list
			$scope.saveNfe();
			//Record current nfe values
			$http({
				url: '../stock/stock_in/' + $scope.idNfes,
				method: 'GET',
			}).then(function successCallback(response) {
				console.log('Nota fechada!');
				alert("Nota Num. " + $scope.idNfes + ", fechada e enviada.");
				location.reload();
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
				$scope.alertNow = false;
				//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
			});
		}
		else {
			console.log("Total errado");
			alert("Total da nota errado, verificar!");
			$scope.alertNow = false;
		}
	}

	$scope.next = function () {
		if ($scope.activeTab==3 && $scope.disableList) {
			// No action
		}
		else {
			$scope.alertNow = false;
			$scope.activeTab += 1;
		}
	}

	$scope.selectVar = function (title,model,idProduct) {

		$scope.alertNow = false;

		$scope.disableVars = !$scope.disableVars;
		$scope.variable.title = title;
		$scope.variable.model = model;
		$scope.variable.idProduct = idProduct;

	}

	$scope.newVar = function (varForm) {

		$scope.alertNow = false;

		if ($scope.variable.variant && $scope.variable.salesPrice && $scope.variable.ncm && varForm.$valid) {

			$scope.variable.idNfes = $scope.idNfes;
	
			newEntry = JSON.stringify ($scope.variable);
	
			console.log("Enviando dados da variação...");
	
			$http.post('../product/var_add', newEntry).then(function successCallback(response) {
	
				console.log("Envio bem sucedido. Variação criada!");
				$scope.alertNow3 = false;	
				getPurchaseList($scope.idNfes);
				getAllProds();
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

		else {
			console.log("Falta de informações para cadastrar variação!");
			$scope.alertNow3 = true;
		}

	}

	// Remove from list
	$scope.delList = function (idPurchaseList,sku) {

		$http.post('../stock/list_del/' + idPurchaseList + '/' + sku).then(function successCallback(response) {
			console.log("Item removido da lista!");
			getPurchaseList($scope.idNfes);
			getAllProds();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});	

	}

	// To renew filter
	$scope.forceFilter = function (searchField,supplier) {
		$scope.search2 = searchField;
		$scope.search.supplier = supplier;
	}

	// Get NFEs from Database and products
	getNFEs(); getProducts(); getAllProds();

});