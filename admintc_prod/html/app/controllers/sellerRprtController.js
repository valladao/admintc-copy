"use strict"
/**
 * Description
 *  	 Seller Report page Controller
 */

app.controller("sellerRprtController", function ($scope, $http) {
  // Variables declaration
  $scope.personalBar1 = "0%"
  $scope.sellerText1 = "0%"
  $scope.personalBar2 = "0%"
  $scope.sellerText2 = "0%"

  $scope.storeDone1 = false
  $scope.storeDone2 = false
  $scope.storeBar11 = "0%"
  $scope.storeBar12 = "0%"
  $scope.storeBar13 = "0%"
  $scope.storeBar21 = "0%"
  $scope.storeBar22 = "0%"
  $scope.storeBar23 = "0%"
  $scope.storeText11 = ""
  $scope.storeText12 = ""
  $scope.storeText13 = ""
  $scope.storeText21 = ""
  $scope.storeText22 = ""
  $scope.storeText23 = ""

  $scope.terracottaDone1 = false
  $scope.terracottaDone2 = false
  $scope.terracottaBar11 = "0%"
  $scope.terracottaBar12 = "0%"
  $scope.terracottaBar13 = "0%"
  $scope.terracottaBar21 = "0%"
  $scope.terracottaBar22 = "0%"
  $scope.terracottaBar23 = "0%"
  $scope.terracottaText11 = ""
  $scope.terracottaText12 = ""
  $scope.terracottaText13 = ""
  $scope.terracottaText21 = ""
  $scope.terracottaText22 = ""
  $scope.terracottaText23 = ""

  $scope.commission1 = "R$ 0,00"
  $scope.commission_text1 = "Sem comissão configurada."
  $scope.commission2 = "R$ 0,00"
  $scope.commission_text2 = "Sem comissão configurada."

  const date = new Date()

  // Get Date
  // Pay attention that for JS, month starts at 0
  $scope.month1 = date.getMonth()
  $scope.year1 = date.getFullYear()

  if ($scope.month1 === 0) {
    // Pay attention that for JS, December is month 11
    $scope.month2 = 11
  } else {
    $scope.month2 = date.getMonth() - 1
  }

  if ($scope.month1 === 0) {
    $scope.year2 = date.getFullYear() - 1
  } else {
    $scope.year2 = date.getFullYear()
  }

  function getCommission() {
    const username = angular
      .element(document.getElementsByName("username")[0])
      .val()

    $http({
      url: "../sales/commission_info/" + username,
      method: "GET",
    }).then(
      function successCallback(response) {
        console.log(response.data)

        $scope.personalBar1 = response.data.personal_target1 * 100 + "%"

        $scope.sellerText1 =
          (response.data.personal_target1 * 100).toFixed(0) + "%"

        switch (true) {
          case response.data.store_target1 >= 1.5:
            $scope.storeText11 = "Meta Atingida!"
            $scope.storeText12 = "Meta Atingida!"
            $scope.storeText13 = "Meta Atingida!"
            $scope.storeDone1 = true
            $scope.storeBar11 = "60%"
            $scope.storeBar12 = "20%"
            $scope.storeBar13 = "20%"
            break

          case response.data.store_target1 >= 1.2:
            $scope.storeText11 = "Meta Atingida!"
            $scope.storeText12 = "Meta Atingida!"
            $scope.storeDone1 = true
            $scope.storeBar11 = "60%"
            $scope.storeBar12 = "20%"
            $scope.storeBar13 =
              ((response.data.store_target1 - 1.2) / 3) * 200 + "%"
            break

          case response.data.store_target1 >= 0.9:
            $scope.storeText11 = "Meta Atingida!"
            $scope.storeText12 =
              ((response.data.store_target1 - 0.9) * 100).toFixed(0) + "%"
            $scope.storeDone1 = true
            $scope.storeBar11 = "60%"
            $scope.storeBar12 =
              ((response.data.store_target1 - 0.9) / 3) * 200 + "%"
            break

          default:
            $scope.storeText11 =
              (response.data.store_target1 * 100).toFixed(0) + "%"
            $scope.storeBar11 = (response.data.store_target1 / 3) * 200 + "%"
            break
        }

        switch (true) {
          case response.data.terracotta_target1 >= 1.5:
            $scope.terracottaText11 = "Meta Atingida!"
            $scope.terracottaText12 = "Meta Atingida!"
            $scope.terracottaText13 = "Meta Atingida!"
            $scope.terracottaDone1 = true
            $scope.terracottaBar11 = "60%"
            $scope.terracottaBar12 = "20%"
            $scope.terracottaBar13 = "20%"
            break

          case response.data.terracotta_target1 >= 1.2:
            $scope.terracottaText11 = "Meta Atingida!"
            $scope.terracottaText12 = "Meta Atingida!"
            $scope.terracottaDone1 = true
            $scope.terracottaBar11 = "60%"
            $scope.terracottaBar12 = "20%"
            $scope.terracottaBar13 =
              ((response.data.terracotta_target1 - 1.2) / 3) * 200 + "%"
            break

          case response.data.terracotta_target1 >= 0.9:
            $scope.terracottaText11 = "Meta Atingida!"
            $scope.terracottaText12 =
              ((response.data.terracotta_target1 - 0.9) * 100).toFixed(0) + "%"
            $scope.terracottaDone1 = true
            $scope.terracottaBar11 = "60%"
            $scope.terracottaBar12 =
              ((response.data.terracotta_target1 - 0.9) / 3) * 200 + "%"
            break

          default:
            $scope.terracottaText11 =
              (response.data.terracotta_target1 * 100).toFixed(0) + "%"
            $scope.terracottaBar11 =
              (response.data.terracotta_target1 / 3) * 200 + "%"
            break
        }

        $scope.commission1 = response.data.commission1.toFixed(2)
        $scope.commission_text1 = response.data.commissionMsg1

        $scope.personalBar2 = response.data.personal_target2 * 100 + "%"
        if (response.data.personal_target2 >= 1) {
          $scope.sellerText2 = "Meta Atingida!"
        } else {
          $scope.sellerText2 =
            (response.data.personal_target2 * 100).toFixed(0) + "%"
        }

        switch (true) {
          case response.data.store_target2 >= 1.5:
            $scope.storeText21 = "Meta Atingida!"
            $scope.storeText22 = "Meta Atingida!"
            $scope.storeText23 = "Meta Atingida!"
            $scope.storeDone1 = true
            $scope.storeBar21 = "60%"
            $scope.storeBar22 = "20%"
            $scope.storeBar23 = "20%"
            break

          case response.data.store_target2 >= 1.2:
            $scope.storeText21 = "Meta Atingida!"
            $scope.storeText22 = "Meta Atingida!"
            $scope.storeDone2 = true
            $scope.storeBar21 = "60%"
            $scope.storeBar22 = "20%"
            $scope.storeBar23 =
              ((response.data.store_target2 - 1.2) / 3) * 200 + "%"
            break

          case response.data.store_target2 >= 0.9:
            $scope.storeText21 = "Meta Atingida!"
            $scope.storeText22 =
              ((response.data.store_target2 - 0.9) * 100).toFixed(0) + "%"
            $scope.storeDone2 = true
            $scope.storeBar21 = "60%"
            $scope.storeBar22 =
              ((response.data.store_target2 - 0.9) / 3) * 200 + "%"
            break

          default:
            $scope.storeText21 =
              (response.data.store_target2 * 100).toFixed(0) + "%"
            $scope.storeBar21 = (response.data.store_target2 / 3) * 200 + "%"
            break
        }

        switch (true) {
          case response.data.terracotta_target2 >= 1.5:
            $scope.terracottaText21 = "Meta Atingida!"
            $scope.terracottaText22 = "Meta Atingida!"
            $scope.terracottaText23 = "Meta Atingida!"
            $scope.terracottaDone1 = true
            $scope.terracottaBar21 = "60%"
            $scope.terracottaBar22 = "20%"
            $scope.terracottaBar23 = "20%"
            break

          case response.data.terracotta_target2 >= 1.2:
            $scope.terracottaText21 = "Meta Atingida!"
            $scope.terracottaText22 = "Meta Atingida!"
            $scope.terracottaDone2 = true
            $scope.terracottaBar21 = "60%"
            $scope.terracottaBar22 = "20%"
            $scope.terracottaBar23 =
              ((response.data.terracotta_target2 - 1.2) / 3) * 200 + "%"
            break

          case response.data.terracotta_target2 >= 0.9:
            $scope.terracottaText21 = "Meta Atingida!"
            $scope.terracottaText22 =
              ((response.data.terracotta_target2 - 0.9) * 100).toFixed(0) + "%"
            $scope.terracottaDone2 = true
            $scope.terracottaBar21 = "60%"
            $scope.terracottaBar22 =
              ((response.data.terracotta_target2 - 0.9) / 3) * 200 + "%"
            break

          default:
            $scope.terracottaText21 =
              (response.data.terracotta_target2 * 100).toFixed(0) + "%"
            $scope.terracottaBar21 =
              (response.data.terracotta_target2 / 3) * 200 + "%"
            break
        }

        if (response.data.commission2)
          $scope.commission2 = response.data.commission2.toFixed(2)
        $scope.commission_text2 = response.data.commissionMsg2
      },
      function errorCallback(response) {
        console.log("Error: " + response.status + " - " + response.statusText)
        //alertNow("Error: " + response.status + " - " + response.statusText,"danger");
      }
    )
  }

  getCommission()
})
