'use strict';
/**
* Description
*  	 Supplier Report page Controller
*/

app.controller('supplier_rprtController', function($scope, $http){

	//Var Initialization
	var currentYear = (new Date()).getFullYear();
	$scope.selectedYear = currentYear;
	var maxYear = (currentYear - 2013) + 1;

	//Build year options
	var range = [];
	range.push(currentYear);
	for (var i = 1; i < maxYear; i++) {
		range.push(currentYear - i);
	}
	$scope.years = range;

	// Function to get NFEs from Database
	function getReport() {

		var sendYear = $scope.selectedYear;

		$http({
			url: '../report/supplier/' + sendYear,
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.reportTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// To select year and get data
	$scope.getData = function () {
		getReport();
	}

	getReport();

});