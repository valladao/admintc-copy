'use strict';
/**
* Description
*  	 Sales page Controller
*/

app.controller('salesController', function($scope, $http, getTerraCotta){

	// General Var initialization
	$scope.salesTable = []; $scope.payTable = [];
	$scope.popup1 = []; $scope.popup2 = []; $scope.popup3 = [];
	$scope.listCount = 0;
	$scope.sale = []; $scope.sale.discount = 0; $scope.sale.total = 0;
	$scope.sale.cost = 0; $scope.sale.payPending = 0; $scope.sale.freight = 0;
	$scope.freight = [];
	$scope.card = []; $scope.money = []; $scope.check = [];
	$scope.deposit = []; $scope.bill = []; $scope.pending = [];
	$scope.change = [];
	//$scope.card.payType = "Pagseguro";
	$scope.card.installment = "1x";
	var newObj = {}; var newEntry = {}; var skus = [];

	//Time initialization
	var todayDate = new Date();
	todayDate.setMinutes(todayDate.getMinutes() - todayDate.getTimezoneOffset());
	$scope.sale.date = todayDate.toISOString().slice(0,10);

	//Initial tab status
	$scope.disablePay = true;
	$scope.disableReview = true;
	$scope.activeTab = 0;

	//Search initialization
	$scope.search = []; var skuPriority = false;
	$scope.cleanSearch = function () {
		$scope.searchFilter = "";
		$scope.search.supplier = "";
		$scope.search.type = "";
		$scope.currentPage = 1;
		$scope.imageActive = false;
	}
	$scope.cleanSearch();

	//Hide payment options
	$scope.showCheck = false;
	$scope.showDeposit = false;
	$scope.showBill = false;
	$scope.showPending = false;
	$scope.showFreight = false;
	$scope.showChange = false;
	$scope.haveFreight = false;

	//Validations initialization
	function cleanValidation() {
		$scope.noCardBanner = false;
		$scope.noCardValue = false;
		$scope.card.value = "";
		$scope.noMoneyValue = false;
		$scope.money.value = "";
		$scope.noCheckValue = false;
		$scope.noCheckDate = false;
		$scope.check.value = "";
		$scope.noDepositValue = false;
		$scope.noDepositBank = false;
		$scope.deposit.value = "";
		$scope.noBillValue = false;
		$scope.noBillDate = false;
		$scope.noBillChannel = false;
		$scope.bill.value = "";
		$scope.noPendingValue = false;
		$scope.noPendingDate = false;
		$scope.noPendingNote = false;
		$scope.pending.value = "";
		$scope.freight.value = "";
		$scope.noChangeSale = false;
		$scope.noChangeSkus = false;
		$scope.noChangeValue = false;
		$scope.change.value = "";
	}
	cleanValidation();

	//Alert initialization
	$scope.alertNow1 = false;
	$scope.alertMsg1 = "";
	$scope.alertNow2 = false;
	$scope.alertMsg2 = "";

	$scope.clearPriority = function () {
		skuPriority = false;
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

			//Get store name from hidden input field and use to filter product
			var text = angular.element(document.getElementsByName('store')[0]).val();
			$scope.store = text;

		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

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

	// Sum all subtotals to show total and costs
	$scope.getTotal = function () {
		var total = 0;
		var cost = 0;
		var qty = 0;
		for (var i = 0; i < $scope.salesTable.length; i++) {
			var product = $scope.salesTable[i];
			total += ((product.salesPrice * product.salesQty) - product.discount);
			cost += (product.unitCost * product.salesQty);
			qty += product.salesQty;
		}

		$scope.subtotal = total;
		$scope.sale.cost = cost;
		$scope.sale.qty = qty;
		return total;

	}

	//When click arrow down...
	$scope.select = function (data) {

		var maxQty = 0;

		switch($scope.store) {
		case 'Moema':
			maxQty = data.store1;
			break;
		case 'Jardins':
			maxQty = data.store2;
			break;
		case 'Morumbi':
			maxQty = data.store4;
			break;
		case 'Museu':
			maxQty = data.store3;
			break;
		}

		if (maxQty >= 1) {

			if (!skus.includes(data.sku)) {

				//Add products in new transfer table
				$scope.salesTable.push({
					'sku':data.sku,
					'title':data.title,
					'model':data.model,
					'variant':data.variant,
					'supplier':data.supplier,
					'type':data.type,
					'salesPrice':data.salesPrice,
					'unitCost':data.unitCost,
					'idVar':data.idVar,
					'idInventory':data.idInventory,
					'maxQty': maxQty,
					'salesQty':1,
					'discount':0,
					'listCount':$scope.listCount
				});
	
				//List to avoid sku duplication in list
				skus.push(data.sku);
	
				$scope.listCount ++;

			}

			else {
				console.log("SKU já incluído!")
			}
	
		}

		else {
			console.log("Quantidade ZERO!")
		}

	}

	//When click +
	$scope.plusQty = function (data) {

		if (data.salesQty < data.maxQty) {
			$scope.salesTable[data.listCount].salesQty ++;
		}

	}

	//When click -
	$scope.lessQty = function (data) {

		if (data.salesQty > 1) {
			$scope.salesTable[data.listCount].salesQty --;
		}

	}

	//When you click X
	$scope.delete = function (index) {

		// BUG => We need to remove here the SKU in sku verification list

		$scope.salesTable.splice(index,1);

		if ($scope.salesTable.length == 0) {
			$scope.sale.discount = 0;
		}

	}

	//When you click "Finalizar Venda"
	$scope.closeList = function (total) {
		if (total > 0) {
			$scope.disablePay = false;
			$scope.activeTab = 1;
		}
		else {
			console.log("Total menor igual a ZERO!");
			$scope.alertNow1 = true;
			$scope.alertMsg1 = "Total menor igual a ZERO!";
		}
	}

	//When you click copy total value
	$scope.getAll = function (type) {

		switch(type){
			case 'card':
				$scope.card.value = $scope.sale.payPending;
				break;
			case 'money':
				$scope.money.value = $scope.sale.payPending;
				break;
			case 'check':
				$scope.check.value = $scope.sale.payPending;
				break;
			case 'deposit':
				$scope.deposit.value = $scope.sale.payPending;
				break;
			case 'bill':
				$scope.bill.value = $scope.sale.payPending;
				break;
			case 'pending':
				$scope.pending.value = $scope.sale.payPending;
				break;
			case 'change':
				$scope.change.value = $scope.sale.payPending;
				break;
		}

	}

	//When you click add payment
	$scope.addPay = function (type) {

		// BUG: Check if value is number

		if ($scope.sale.payPending > 0) {

			var isValid = false;

			var payType = ""; var banner = ""; var mode = "";
			var value = 0; var installment = ""; var date = "";
			var bank = ""; var channel = ""; var note = "";
	
			switch(type){
				case 'card':
					if ($scope.card.banner && $scope.card.value) {

						payType = $scope.card.payType;
						banner = $scope.card.banner;
						switch(banner){
							case 'Master Débito':
							banner = 'Master';
							mode = 'Débito';
							break;
							case 'Master Crédito':
							banner = 'Master';
							mode = 'Crédito';
							break;
							case 'Visa Débito':
							banner = 'Visa';
							mode = 'Débito';
							break;
							case 'Visa Crédito':
							banner = 'Visa';
							mode = 'Crédito';
							break;
							case 'Elo Débito':
							banner = 'Elo';
							mode = 'Débito';
							break;
							case 'Elo Crédito':
							banner = 'Elo';
							mode = 'Crédito';
							break;
							case 'AMEX':
							banner = 'AMEX';
							mode = 'Crédito';
							break;
							case 'Hipercard':
							banner = 'Hipercard';
							mode = 'Crédito';
							break;
							case 'Hiper':
							banner = 'Hipercard';
							mode = 'Débito';
							break;
							case 'Dinners':
							banner = 'Dinners';
							mode = 'Crédito';
							break;
							case 'Banricompras':
							banner = 'Banricompras';
							mode = 'Débito';
							break;
						}
						value = $scope.card.value;
						installment = $scope.card.installment;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.card.banner) {
							$scope.noCardBanner = true;
						}
						if (!$scope.card.value) {
							$scope.noCardValue = true;
						}
					}
					break;
				case 'money':
					if ($scope.money.value) {
						payType = "Dinheiro";
						value = $scope.money.value;
						cleanValidation();
						isValid = true;
					}
					else {
						$scope.noMoneyValue = true;
					}
					break;
				case 'check':
					if ($scope.check.value && $scope.check.date) {
						payType = "Cheque";
						value = $scope.check.value;
						date = $scope.check.date;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.check.value) {
							$scope.noCheckValue = true;
						}
						if (!$scope.check.date) {
							$scope.noCheckDate = true;
						}
					}
					break;
				case 'deposit':
					if ($scope.deposit.value && $scope.deposit.bank) {
						payType = "Depósito";
						value = $scope.deposit.value;
						bank = $scope.deposit.bank;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.deposit.value) {
							$scope.noDepositValue = true;
						}
						if (!$scope.deposit.bank) {
							$scope.noDepositBank = true;
						}
					}
					break;
				case 'bill':
					if ($scope.bill.value && $scope.bill.date && $scope.bill.channel) {
						payType = "Boleto";
						value = $scope.bill.value;
						date = $scope.bill.date;
						channel = $scope.bill.channel;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.bill.value) {
							$scope.noBillValue = true;
						}
						if (!$scope.bill.date) {
							$scope.noBillDate = true;
						}
						if (!$scope.bill.channel) {
							$scope.noBillChannel = true;
						}
					}
					break;
				case 'pending':
					if ($scope.pending.value && $scope.pending.date && $scope.pending.note) {
						payType = "À cobrar";
						value = $scope.pending.value;
						date = $scope.pending.date;
						note = $scope.pending.note;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.pending.value) {
							$scope.noPendingValue = true;
						}
						if (!$scope.pending.date) {
							$scope.noPendingDate = true;
						}
						if (!$scope.pending.note) {
							$scope.noPendingNote = true;
						}
					}
					break;
				case 'change':
					if ($scope.change.sale && $scope.change.skus && $scope.change.value) {
						payType = "Troca";
						value = $scope.change.value;
						note = "Venda: " + $scope.change.sale + ". SKUs: " + $scope.change.skus;
						cleanValidation();
						isValid = true;
					}
					else {
						if (!$scope.change.sale) {
							$scope.noChangeSale = true;
						}
						if (!$scope.change.skus) {
							$scope.noChangeSkus = true;
						}
						if (!$scope.change.value) {
							$scope.noChangeValue = true;
						}
					}
					break;
			}

			if ($scope.sale.payPending - value >= 0 && isValid) {

				//Prepare date
				if (date) {
					var dateReady = date.toISOString().slice(0,10);
				}

				//Add products in new transfer table
				$scope.payTable.push({
					'payType':payType,
					'banner':banner,
					'mode':mode,
					'value':value,
					'installment':installment,
					'date': dateReady,
					'bank':bank,
					'channel':channel,
					'note':note
				});
		
			}

		}

	}

	// To calculate all included payment
	$scope.payTotal = function () {

		var total = 0;
		for (var i = 0; i < $scope.payTable.length; i++) {
			var payItem = $scope.payTable[i];
			total += Number(payItem.value);
		}

		return total;

	}

	$scope.open1 = function () {
		$scope.popup1.opened = true;
	}

	$scope.open2 = function () {
		$scope.popup2.opened = true;
	}

	$scope.open3 = function () {
		$scope.popup3.opened = true;
	}

	// To close payment
	$scope.closePay = function () {
		if ($scope.sale.payPending.toFixed(2) == 0) {
			$scope.disableReview = false;
			$scope.activeTab = 2;
			cleanValidation();
		}
		else {
			console.log("Pendente: " + $scope.sale.payPending);
			$scope.alertNow = true;
			$scope.alertMsg = "Pagamento pendente:\nR$ " + $scope.sale.payPending.toFixed(2);
		}
	}

	// To activate image
	$scope.activateImage = function () {
		$scope.imageActive = !$scope.imageActive;
	}

	// To show more payment options
	$scope.showMe = function (option) {
		switch (option){
			case 'check':
			$scope.showCheck = !$scope.showCheck;
			break;
			case 'deposit':
			$scope.showDeposit = !$scope.showDeposit;
			break;
			case 'bill':
			$scope.showBill = !$scope.showBill;
			break;
			case 'pending':
			$scope.showPending = !$scope.showPending;
			break;
			case 'freight':
			$scope.showFreight = !$scope.showFreight;
			break;
			case 'change':
			$scope.showChange = !$scope.showChange;
			break;
		}
	}

	//When you click X
	$scope.delPay = function (index) {

		$scope.payTable.splice(index,1);

	}

	//When you click close sales button
	$scope.salesClose = function () {

		if ($scope.sale.total > 0 && $scope.sale.payPending.toFixed(2) == 0) {

			if ($scope.sale.ecommerce) {
				$scope.sale.store = "shopify";
			}
			else {
				$scope.sale.store = $scope.store.toLowerCase();
			}

			if ($scope.sale.salesman) {
				var username = $scope.sale.salesman;
			}
			else {
				//Get username name from hidden input field
				var username = angular.element(document.getElementsByName('username')[0]).val();
			}
	
			// IMPORTANTE... To add sale info, salesTable and payTable
			newObj.date = $scope.sale.date;
			newObj.store = $scope.sale.store;
			newObj.discount = $scope.sale.discount;
			newObj.totalPrice = $scope.sale.total;
			newObj.totalCost = $scope.sale.cost;
			newObj.username = username.toLowerCase();
			newObj.salesTable = $scope.salesTable;
			newObj.payTable = $scope.payTable;
			newObj.freight = $scope.sale.freight;
	
			newEntry = JSON.stringify (newObj);
	
			$http.post('../sales/add_sale', newEntry).then(function successCallback(response) {
		
				console.log("Envio bem sucedido. Transferencia efetuada!");
				alert(response.data);
				location.reload();
	
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	$scope.addFreight = function () {

		$scope.sale.freight = Number($scope.freight.value);

		$scope.haveFreight = true;

	}

	// Get Salesman to build select options
	function getUser() {
		
		$http({
			url: '../helper/get_users',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.salesman = response.data;
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
		});

	}

	getProducts(); getUser();

});