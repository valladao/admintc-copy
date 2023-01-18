'use strict';
/**
* Description
*  	 sales_rprt page Controller
*/

app.controller('sales_rprtController', function($scope,$http){

	// Var initialization
	$scope.popup1 = []; $scope.search = []; $scope.search2 = [];
	$scope.report = []; $scope.total = {};

	// Time initialization
	var todayDate = new Date();
	$scope.search.date = todayDate;

	// Open time popup
	$scope.open1 = function () {
		$scope.popup1.opened = true;
	}

	// Function to get NFEs from Database
	function getReport(date) {

		$http({
			url: '../report/daily/' + date,
			method: 'GET',
		}).then(function successCallback(response) {

			$scope.salesTable = response.data.sales;
			$scope.saleList = response.data.saleList;
			$scope.payList = response.data.payList;

			$scope.total.all = 0; $scope.total.moema = 0; $scope.total.jardins = 0;
			$scope.total.morumbi = 0; $scope.total.ecommerce = 0; $scope.total.project = 0;
			$scope.total.museu = 0;

			for (var i = $scope.salesTable.length - 1; i >= 0; i--) {
				$scope.total.all += Number($scope.salesTable[i].totalPrice);
				switch ($scope.salesTable[i].store) {
					case "moema":
						$scope.total.moema += Number($scope.salesTable[i].totalPrice);
						break;
					case "jardins":
						$scope.total.jardins += Number($scope.salesTable[i].totalPrice);
						break;
					case "morumbi":
						$scope.total.morumbi += Number($scope.salesTable[i].totalPrice);
						break;
					case "museu":
						$scope.total.museu += Number($scope.salesTable[i].totalPrice);
						break;
					case "shopify":
						$scope.total.ecommerce += Number($scope.salesTable[i].totalPrice);
						break;
					case "project":
						$scope.total.project += Number($scope.salesTable[i].totalPrice);
						break;
				}
			}

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get NFEs from Database
	function getReport2(idSales) {

		$http({
			url: '../report/sales/' + idSales,
			method: 'GET',
		}).then(function successCallback(response) {

			$scope.saleInfo = response.data.sales;
			$scope.saleList2 = response.data.saleList;
			$scope.payList2 = response.data.payList;

			console.log(response.data);

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Get sales data by date
	$scope.getSales = function () {

		var date = $scope.search.date;
		date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
		var finalDate = date.toISOString().slice(0,10);
		$scope.report.date = finalDate;
		getReport(finalDate);

	}

	// Sum all subtotals to show total
	$scope.getTotal = function (idSales) {

		var total = 0;
		for (var i = 0; i < $scope.saleList.length; i++) {
			if ($scope.saleList[i].idSales == idSales) {
				var product = $scope.saleList[i];
				total += ((product.price * product.qty) - product.discount);
			}
		}

		return total;

	}

	// Get sales data by sales id
	$scope.getSales2 = function () {
		getReport2($scope.search2.idSales);
	}

	// Sum all subtotals to show total
	$scope.getTotal2 = function () {

		var total = 0;
		for (var i = 0; i < $scope.saleList2.length; i++) {
			var product = $scope.saleList2[i];
			total += ((product.price * product.qty) - product.discount);
		}

		return total;

	}

});