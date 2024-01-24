'use strict';
/**
* Description
*  	 Product page Controller
*/

app.controller('productsController', function($scope,$http, getTerraCotta){

	// Var initialization
	$scope.product = []; $scope.productTable = []; $scope.locked = true;
	var enableEdit = false; $scope.alertNow = false; $scope.alertMsg = "";
	$scope.loadMsg = "Carregando - Aguarde..."; $scope.waitLoad = true;
	var newEntry; newEntry = {};

	// Function to get NFEs from Database
	function getProducts() {

		$http({
			url: '../stock/get',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.productTable = response.data;
			$scope.loadMsg = ""; $scope.waitLoad = false;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Get Types from Database
	getTerraCotta.types().then(function successCallback(response) {
			$scope.types = response.data;
//			console.log($scope.types);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// Get Models from Database
	getTerraCotta.models().then(function successCallback(response) {
			$scope.models = response.data;
//			console.log($scope.models);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// To get the variant origin based on supplier
	function getOrigin() {

		if ($scope.product.sku) {

			var newEntry = $scope.product.supplier;

			$http.post('../helper/get_origin', newEntry).then(function successCallback(response) {
				$scope.product.origin = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// To get the variant origin based on supplier
	function getVariants(idProduct) {

		if ($scope.product.sku) {

			//var newEntry = $scope.product.supplier;

			$http.get('../helper/get_variants/' + idProduct).then(function successCallback(response) {
				$scope.variants = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// To get purchases from SKU
	function getPurchase() {

		if ($scope.product.sku) {

			var sku = $scope.product.sku;

			$http.get('../helper/get_purchase/' + sku).then(function successCallback(response) {
				$scope.purchase = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// To get sales for SKU
	function getSales() {

		if ($scope.product.sku) {

			var sku = $scope.product.sku;

			$http.get('../helper/get_sales3/' + sku).then(function successCallback(response) {
				$scope.sales = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// To get moves
	function getMoves() {

		if ($scope.product.sku) {

			var sku = $scope.product.sku;

			$http.get('../helper/get_moves/' + sku).then(function successCallback(response) {
				$scope.moves = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// To get transfer
	function getTransfer() {

		if ($scope.product.sku) {

			var sku = $scope.product.sku;

			$http.get('../helper/get_transfer2/' + sku).then(function successCallback(response) {
				$scope.transfers = response.data;
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// Check if SKU matches everytime you change field
	$scope.checkSku = function () {
		for (var i = 0; i < $scope.productTable.length; i++) {
			if ($scope.productTable[i].sku == $scope.skuInput) {
				$scope.product.title = $scope.productTable[i].title;
				$scope.product.supplier = $scope.productTable[i].supplier;
				$scope.product.type = $scope.productTable[i].type;
				$scope.product.model = $scope.productTable[i].model;
				$scope.product.variant = $scope.productTable[i].variant;
				$scope.product.sku = $scope.productTable[i].sku;
				$scope.product.idProduct = $scope.productTable[i].idProduct;
				$scope.product.salesPrice = $scope.productTable[i].salesPrice;
				$scope.product.store1 = $scope.productTable[i].store1;
				$scope.product.store2 = $scope.productTable[i].store2;
				$scope.product.store3 = $scope.productTable[i].store3;
				$scope.product.total = $scope.productTable[i].total;
				$scope.product.picture = $scope.productTable[i].picture;
				$scope.product.unitCost = $scope.productTable[i].unitCost;
				$scope.product.ncm = $scope.productTable[i].ncm;
				$scope.product.idVar = $scope.productTable[i].idVar;
				$scope.product.idInventory = $scope.productTable[i].idInventory;
				$scope.product.barcode = $scope.productTable[i].barcode;
				$scope.product.vendor = $scope.productTable[i].vendor;
				$scope.product.idOptions = $scope.productTable[i].idOptions;
				$scope.alertNow = false;
				getOrigin();
				getVariants($scope.productTable[i].idProduct);
				getPurchase();
				getSales();
				getMoves();
				getTransfer();

			}
			else {
				// Produto não encontrado!
			}

		}
	}

	//To open edit product and send the new information
	$scope.editProduct = function () {
		if ($scope.product.sku) {
			if ($scope.locked) {
				$scope.locked = false;
			}
			else {
				updateProduct();
			}
		}
	}

	// Function responsible to update product
	function updateProduct() {

		console.log("Editando...");
		var entry = {};

		entry['title'] = $scope.product.title;
		entry['type'] = $scope.product.type;
		entry['model'] = $scope.product.model;
		entry['variant'] = $scope.product.variant;
		entry['salesPrice'] = $scope.product.salesPrice;
		entry['idProduct'] = $scope.product.idProduct;
		entry['sku'] = $scope.product.sku;
		entry['idVar'] = $scope.product.idVar;
		entry['idInventory'] = $scope.product.idInventory;
		entry['barcode'] = $scope.product.barcode;
		entry['stockQty'] = $scope.product.total;
		entry['vendor'] = $scope.product.vendor;
		entry['idOptions'] = $scope.product.idOptions;

		newEntry = JSON.stringify(entry);
	
		console.log("Enviando dados do produto...");

		$http.post('../product/prod_updt', newEntry).then(function successCallback(response) {
	
			console.log("Envio bem sucedido. Produto atualizado!");
			$scope.locked = true;
//			alert("Nota Num. " + $scope.idNfes + ", fechada e enviada.");
//			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	// Delete product, only zero quantity
	$scope.delProd = function (total,sku) {
		if (total == 0 || total == null) {
			console.log("Pronto para deletar...");

			$http({
				url: '../product/prod_del/' + sku,
				method: 'POST',
			}).then(function successCallback(response) {
				alert("Produto " + response.data + " apagado.");
				location.reload();
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
				//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
			});

		}
		else {
			console.log("Produto ainda em estoque!");
			console.log("Total: " + total);
			$scope.alertNow = true;
			$scope.alertMsg = "Produto ainda em estoque!";
		}
	}

	// When you click the Fiscal tab
	$scope.clickFiscal = function () {
		getOrigin();
	}

	getProducts();

});