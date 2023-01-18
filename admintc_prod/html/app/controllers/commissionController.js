"use strict"
/**
 * Description
 *  	 Seller Report page Controller
 */

app.controller("commissionController", function ($scope, $http) {
  // Get Salesman to build select options
  function getUser() {
    $http({
      url: "../helper/get_users",
      method: "GET",
    }).then(
      function successCallback(response) {
        $scope.salesman = response.data
      },
      function errorCallback(response) {
        console.log("Error: " + response.status + " - " + response.statusText)
      }
    )
  }

  getUser()
})
