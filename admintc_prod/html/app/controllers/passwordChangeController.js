"use strict"
/**
 * Description
 *  	 Password Change page Controller
 */

app.controller("passwordChangeController", function ($scope, $http) {
  // Variables declaration
  $scope.password = []
  $scope.password.new = ""
  $scope.error = ""
  $scope.wait = false
  $scope.updated = false

  const cleanForm = () => {
    $scope.password.current = ""
    $scope.password.new = ""
    $scope.password.repeat = ""
  }

  cleanForm()

  // Function change password
  $scope.changePassword = function () {
    // Reset /declare vars
    let newObj = {}
    let newEntry = {}
    $scope.error = ""

    // Check if current password has been entered
    if (!$scope.password.current) {
      $scope.error = "Obrigatório informar a senha atual..."
      return
      // Check if password is at least 6 characters
    } else if ($scope.password.new.length < 6) {
      $scope.error = "Sua senha deve ter pelo menos 6 caracteres..."
      return
      // Check if password matches
    } else if ($scope.password.new != $scope.password.repeat) {
      $scope.error = "A confirmação de senha não bate..."
      return
    } else {
      $scope.wait = true
      newObj.password = $scope.password.current
      newObj.new = $scope.password.new
      newObj.repeat = $scope.password.repeat
      // Get username from hidden field and attach
      newObj.username = angular
        .element(document.getElementsByName("username")[0])
        .val()

      newEntry = JSON.stringify(newObj)

      $http.post("../helper/change_password", newEntry).then(
        function successCallback(response) {
          console.log("Senha atualizada!")
          alert(response.data)
          $scope.updated = true
          $scope.wait = false
          cleanForm()
        },
        function errorCallback(response) {
          console.log("Error: " + response.status + " - " + response.statusText)
          $scope.error = response.data
          $scope.wait = false
          cleanForm()
          alert(response.data)
        }
      )
    }
  }
})
