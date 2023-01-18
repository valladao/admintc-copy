'use strict';
/**
* Description
*  	 Stock page Controller
*/

app.controller('stockController', function($scope, $http, getTerraCotta){

	//Var initialization
	$scope.search = []; var skuPriority = false; $scope.maxSize = 5;

	$scope.cleanSearch = function () {
		$scope.searchFilter = "";
		$scope.search.supplier = "";
		$scope.search.type = "";
		$scope.currentPage = 1;
		$scope.itemsPerPage = 10;
		$scope.nonZero = false;
		$scope.onlyZero = false;
		$scope.hasPicture = false;
		$scope.noPicture = false;
	}
	$scope.cleanSearch();

	$scope.clearPriority = function () {
		skuPriority = false;
	}

	// Get Types from Database
	getTerraCotta.types().then(function successCallback(response) {
			$scope.types = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	// Get Suppliers from Database
	getTerraCotta.suppliers().then(function successCallback(response) {
			$scope.suppliers = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	//Used when you click the pagination button (check this out)
	$scope.pageChanged = function () {
		// body...
	}

	//Three functions responsible for custom filters
	$scope.greaterThan = function (prop, val) {
		return function(item){
			return item[prop] > val;
		}
	}

	$scope.equalZero = function (prop) {
		return function(item){
			return item[prop] == 0;
		}
	}

	$scope.emptyOrNull = function (item) {
		return !(item.picture === null || item.picture.trim().length === 0)
	}

	$scope.notEmpty = function (item) {
		return (item.picture.trim().length === 0)
	}

	function removeAccents(value) {
		return value
			.replace(/á/g, 'a')
			.replace(/â/g, 'a')
			.replace(/ã/g, 'a')
			.replace(/é/g, 'e')
			.replace(/ê/g, 'e')
			.replace(/í/g, 'i')
			.replace(/ó/g, 'o')
			.replace(/ô/g, 'o')
			.replace(/õ/g, 'o')
			.replace(/ú/g, 'u')
			.replace(/ç/g, 'c');
	}

	$scope.ignoreAccents = function (item) {
		if (!$scope.searchFilter) {
			return true;
		}
		else {
			if (item.sku == $scope.searchFilter) {
				skuPriority = true;
				return true;
			}
			else {
				var jsonstr = JSON.stringify(item);
				var list = removeAccents(jsonstr.toLowerCase());
				var searchterm = removeAccents($scope.searchFilter.toLowerCase());
				if (!skuPriority) {
					return list.indexOf(searchterm) > -1;
				}
				else {
					return false;
				}
				
			}
		}
	};

	// Function to get NFEs from Database
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

	$scope.delProd = function (total,sku) {
		if (total == 0 || total == null) {
			console.log("Pronto para deletar...");

			$http({
				url: '../product/prod_del/' + sku,
				method: 'POST',
			}).then(function successCallback(response) {
				alert("Produto " + response.data + " apagado.");
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}
		else {
			console.log("Produto ainda em estoque!");
			console.log("Total: " + total);
		}

	}

	getProducts();

});