'use strict';
/**
* Description
*  	 Dashboard page Controller
*/

app.controller('dashboardController', function($scope, $http){

	//Var initialization
	$scope.storeFilter = [];

	// Function to get new stock input
	function getCouters() {

		$http({
			url: '../helper/get_counters',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.counters = response.data;
			//Get store name from hidden input field and use to filter product
			var store = angular.element(document.getElementsByName('store')[0]).val();
			$scope.store = store;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get new stock input
	function getSales() {

		$http({
			url: '../helper/today_sales',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.todaySales = response.data;

			//Get store name from hidden input field and use to filter product
			var store = angular.element(document.getElementsByName('store')[0]).val();
			$scope.storeFilter.store = store;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// To arrange fiscal
	$scope.isFiscal = function (data) {
		return data["fiscal?"];
	}

	getSales(); getCouters();

});