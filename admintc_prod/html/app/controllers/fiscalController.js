'use strict';
/**
* Description
*  	 Fiscal page Controller, and filters used to prepare data
*/

app.controller('fiscalController', function($scope, $http){

	//Var initialization
	var cleanList; cleanList = {};
	$scope.search = [];
	$scope.search2 = [];
	$scope.moema = [];
	$scope.moema.destination = "Moema";
	$scope.jardins = [];
	$scope.jardins.destination = "Jardins";
	$scope.morumbi = [];
	$scope.morumbi.destination = "Morumbi";
	$scope.museu = [];
	$scope.museu.destination = "Museu";
	$scope.storeFilter = [];
	var newObj = {};
	var newEntry = {};

	// Function to get Fiscal Register (products that needs to register on fiscal application) from Database
	function getFiscal() {

		$http({
			url: '../helper/get_fiscal',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.fiscalTable = response.data;

			//Get store name from hidden input field and use to filter product
			var store = angular.element(document.getElementsByName('store')[0]).val();
			$scope.store = store;
			$scope.search.stockName = store;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get Fiscal Register (products that needs to register on fiscal application) from Database
	function getTransfer() {

		$http({
			url: '../helper/get_transfer',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.transferTable = response.data;

			//Get store name from hidden input field and use to filter product
			var store = angular.element(document.getElementsByName('store')[0]).val();
			$scope.store = store;
			$scope.search2.origin = store;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get Fiscal Register (products that needs to register on fiscal application) from Database
	function getSales() {

		$http({
			url: '../helper/get_sales2',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.salesTable = response.data;

			//Get store name from hidden input field and use to filter product
			var store = angular.element(document.getElementsByName('store')[0]).val();
			$scope.storeFilter.store = store;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	$scope.cleanList = function () {
		// Function to clean the list, after load it in fiscal tool

		cleanList = $scope.fiscalTable

		$http.post('../helper/clean_fiscal', cleanList).then(function successCallback(response) {
			console.log("Lista de criação de produtos apagada!");
			alert("Lista de criação de produtos apagada!");
			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	// To clean NFE transfer list
	$scope.cleanTransfer = function (origin,dest) {

		// To add origin and destination
		newObj.origin = origin;
		newObj.dest = dest;
		newEntry = JSON.stringify (newObj);

		$http.post('../helper/clean_transfer', newEntry).then(function successCallback(response) {
			console.log("Lista de transferência apagada!");
			alert("Lista de transferência apagada!");
			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	// To clean recipt in database
	$scope.receiptDone = function (idSales) {
//		console.log("Cliquei! Venda: " + idSales);

		$http.post('../helper/receipt_done/' + idSales).then(function successCallback(response) {
			console.log("Venda " + idSales + " apagada.");
			getSales();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});	

	}

	getFiscal(); getTransfer(); getSales();

});