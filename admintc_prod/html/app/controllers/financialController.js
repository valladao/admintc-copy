'use strict';
/**
* Description
*  	 Financial page Controller
*/

app.controller('financialController', function($scope, $http){

	// Function to get new stock input
	function getInput() {

		$http({
			url: '../helper/get_input',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.inputTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get sales list cost per supplier
	function getCost() {

		$http({
			url: '../helper/get_cost',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.costTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get payment list
	function getPayment() {

		$http({
			url: '../helper/get_payment',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.payTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get sale list
	function getSales() {

		$http({
			url: '../helper/get_sales',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.salesTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get move list
	function getMove() {

		$http({
			url: '../helper/get_move',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.moveTable = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// Function to get move list
	function getMoveList() {

		$http({
			url: '../helper/get_moveList',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.moveListTable = response.data;
			console.log($scope.moveListTable);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

	}

	// To build the description text
	$scope.getDesc = function (item) {

		var text = "";

		if (item.banner) {
			text += item.banner + " " + item.mode.toLowerCase();
			if (item.installment != "1x") {
				text += ". Parcelas: " + item.installment;
			}
		}

		if (item.payType == "Cheque") {
			text += "Descontar em: " + item.payDate;
		}

		if (item.bank) {
			text += "Banco: " + item.bank;
		}

		if (item.payType == "Boleto") {
			text += "Canal: " + item.channel + ". Vencimento: " + item.payDate;
		}

		if (item.payType == "À cobrar") {
			text += "À pagar em: " + item.payDate + ". " + item.note;
		}

		if (item.payType == "Troca") {
			text += "Troca. " + item.note;
		}

		return text;
	}

	// Javascript properCase
	function properCase(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}

	// To build the category text for moves
	$scope.getCategory = function (reason,store) {

		var text = "";

		switch (reason) {
			case "Devolução de compra":
				text = "CPV:Venda " + properCase(store);
				break;
			case "Compra cancelada na loja por erro":
				text = "CPV:Venda " + properCase(store);
				break;
			case "Compra cancelada na Internet":
				text = "CPV:Venda Shopify";
				break;
			case "Produto na nota, mas não chegou":
			case "Ramificando em vários produtos":
			case "Produto reencontrado no estoque":
			case "Produto chegou quebrado":
			case "Produto desaparecido":
			case "Presente ou brinde":
				text = "Despesas Gerais:Pendência de Estoque";
				break;
			default: 
				text = "Despesas " + properCase(store) + ":Perda de Estoque";
		}

		return text;
	}

	$scope.moveNeg = function (moveTotal) {
		if (moveTotal < 0) {
			return String(Math.abs(moveTotal)).replace('.',',');
		}
	}

	$scope.movePos = function (moveTotal) {
		if (moveTotal > 0) {
			return String(Math.abs(moveTotal)).replace('.',',');
		}
	}

	$scope.costAbs = function (changeQty,unitCost) {
		return String(Math.round((Math.abs(changeQty * unitCost))*100)/100).replace('.',',');
	}

	$scope.getNote = function (reason,note,username,changeSale) {
		var text = username + " - " + reason + ": " + note;
		if (changeSale) {
			text += ". Venda original: ";
			text += changeSale;
		}
		return text;
	}

	$scope.nonZero = function (item) {
		if (item.freight == 0) {
			return false;
		}
		else {
			return true;
		}
	}

	$scope.cleanStock = function () {
		// Function to clean the list, after load it in fiscal tool

		$http.post('../helper/clean_stock').then(function successCallback(response) {
			console.log("Lista de estoque e custo de venda apagada!");
			alert("Lista de estoque e custo de venda apagada!");
			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	$scope.cleanPay = function () {
		// Function to clean the list, after load it in fiscal tool

		$http.post('../helper/clean_payments').then(function successCallback(response) {
			console.log("Lista de pagamentos apagada!");
			alert("Lista de pagamentos apagada!");
			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	$scope.cleanSales = function () {
		// Function to clean the list, after load it in fiscal tool

		$http.post('../helper/clean_sales').then(function successCallback(response) {
			console.log("Lista de venda apagada!");
			alert("Lista de venda apagada!");
			location.reload();
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	getInput(); getCost(); getPayment();
	getSales(); getMove(); getMoveList();

});