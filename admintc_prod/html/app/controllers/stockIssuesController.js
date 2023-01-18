'use strict';
/**
* Description
*  	 Check Stock Issues page Controller
*/

app.controller('stockIssuesController', function($scope, $http){

	// Function to get NFEs from Database
	function getIssues() {

		$http({
			url: '../stock/get_issues',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.errors = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	 $scope.checkedIssue = function (idError) {
		// Function to clean the list, after load it in fiscal tool

		$http.post('../helper/clean_issue/' + idError).then(function successCallback(response) {
			getIssues();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	getIssues();

});