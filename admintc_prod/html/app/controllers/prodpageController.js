'use strict';
/**
* Description
*  	 prodpage page Controller
*/

app.controller('prodpageController', function($scope,$http,getTerraCotta,$window){

	// Var initialization
	$scope.productTable = []; $scope.product = []; $scope.internet = [];
	$scope.special = []; $scope.shopify = []; $scope.filter = {}; 
	$scope.crib = []; $scope.google = []; 
	$scope.aux = [];
	$scope.shopify.details = ""; $scope.shopify.tags = "";
	$scope.activeTab = 0;
	$scope.itemsPerPage = 10; $scope.currentPage = 1; $scope.maxSize = 5;
	$scope.internet.sizetype = "1D"; 
	//$scope.aux.type = "Figura";
	var skuPriority = false;
	$scope.eventBaptism = false; $scope.titleDisable = true;
	$scope.googleTitleMax = 60;

	// Checkbox initialization
	$scope.appeal = []; $scope.accessory = []; $scope.event = [];

	// Default crib checkbox
	$scope.crib.jesus = true; $scope.crib.mary = true; $scope.crib.joseph = true;
	$scope.crib.melchior = true; $scope.crib.baltasar = true; $scope.crib.gaspar = true;

	// Tab initialization
	$scope.prodTab = true; $scope.shopifyTab = true; $scope.googleTab = true;

	$scope.clearPriority = function () {
		skuPriority = false;
	}

	// Function to get draft Products from Database
	function getNewProdList() {

		$http({
			url: '../internet/get_newProdList',
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.productTable = response.data;
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

	//Used when you click the pagination button (check this out)
	$scope.pageChanged = function () {
		// body...
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

	$scope.firstSku = function (item) {
		if (!$scope.filter.search) {
			return true;
		}
		else {
			if (item.sku == $scope.filter.search) {
				skuPriority = true;
				return true;
			}
			else {
				var jsonstr = JSON.stringify(item);
				var list = removeAccents(jsonstr.toLowerCase());
				var searchterm = removeAccents($scope.filter.search.toLowerCase());
				if (!skuPriority) {
					return list.indexOf(searchterm) > -1;
				}
				else {
					return false;
				}
				
			}
		}
	};

	// To get the variant origin based on supplier
	function getVariants(idProduct) {

		if ($scope.product.sku) {

			var newEntry = $scope.product.supplier;

			$http.get('../helper/get_variants/' + idProduct).then(function successCallback(response) {
				$scope.variants = response.data;
				//console.log($scope.variants);
			}, function errorCallback(response) {
				console.log("Error: " + response.status + " - " + response.statusText);
			});

		}

	}

	// Click and select the SKU
	$scope.selectSKU = function (item) {

		$scope.product.title = item.title;
		$scope.product.supplier = item.supplier;
		$scope.product.type = item.type;
		$scope.product.model = item.model;
		$scope.product.variant = item.variant;
		$scope.product.sku = item.sku;
		$scope.product.picture = item.picture;
		$scope.product.salesPrice = item.salesPrice;

		$http({
			url: '../internet/get_shopifyInfo/' + item.idProduct,
			method: 'GET',
		}).then(function successCallback(response) {
			$scope.fromShopify = response.data.product;
			$scope.images = response.data.product.images;
			$scope.oldTags = response.data.product.tags;
//			$scope.shopify.description = $scope.fromShopify.body_html;
//			console.log($scope.fromShopify);
		}, function errorCallback(response) {
			console.log("Error: " + response.status + " - " + response.statusText);
			console.log(response);
			//alertNow("Error: " + response.status + " - " + response.statusText,"danger");
		});

		getVariants(item.idProduct);
		setFields($scope.product.type,$scope.product.supplier);
		$scope.activeTab = 1;
		$window.scrollTo(0, 0);

		$scope.prodTab = false;

	}

	// Set fields based on type and supplier
	function setFields(type,supplier) {

		//Set no configuration
		$scope.enableSubtype = false;
		$scope.enableFigure = false;
		$scope.enableMaterial = false;
		$scope.enablePainting = false;
		$scope.enableColor = false;
		$scope.enableShape = false;
		$scope.enableStyle = false;
		$scope.enableMedal = false;
		$scope.enableStoneSize = false;
		$scope.enableChainMaterial = false;

		switch (supplier) {

			case "Ateliê NS das Vitórias":
				$scope.internet.material = "Pó de Mármore";
				$scope.disableMaterial = true;
				$scope.internet.country = "Nacional";
				$scope.disableCountry = true;
				break;

			case "CEPO":
				$scope.internet.material = "Madeira";
				$scope.disableMaterial = true;
				$scope.internet.country = "Nacional";
				$scope.disableCountry = true;
				break;

			case "Azzano":
				$scope.internet.country = "Italiano";
				$scope.disableCountry = true;
				break;

			case "PEMA":
				$scope.internet.material = "Madeira";
				$scope.disableMaterial = true;
				$scope.internet.country = "Italiano";
				$scope.disableCountry = true;
				break;

		}
		
		switch (type) {

			case "Terços":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				$scope.enableColor = true;
				$scope.enableShape = true;
				$scope.enableMedal = true;
				$scope.enableStoneSize = true;
				$scope.enableChainMaterial = true;

				$scope.titleTemplate = "Subtype+Material+Other";

				break;

			case "Jóias":
				$scope.enableSubtype = true;
				$scope.enableFigure = true;
				$scope.enableMaterial = true;
				$scope.enableShape = true;
				break;

			case "Primeira Comunhão":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				$scope.internet.baseFigure = "Diversos";
				$scope.disableBaseFigure = true;
				break;

			case "Imagens":

				$scope.enableFigure = true;
				$scope.enableMaterial = true;
				$scope.enablePainting = true;

				switch (supplier) {

					case "Ateliê NS das Vitórias":
						$scope.titleTemplate = "Type+Figure+Painting";
					break;

					case "CEPO":
						$scope.titleTemplate = "Type+Figure+Material";
					break;

					default:
					break;
				}

				break;

			case "Escapulários":
				$scope.enableMaterial = true;
				$scope.titleTemplate = "Type+Material+Other";
				break;

			case "Porta Santo":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				$scope.enablePainting = true;
				break;

			case "Porta Terço":
				$scope.enableFigure = true;
				$scope.enableMaterial = true;
				$scope.titleTemplate = "Type+Material+Other";
				break;

			case "Diversos":

				$scope.enableSubtype = true;
				$scope.enableMaterial = true;

				switch (supplier) {

					case "Ateliê NS das Vitórias":
						$scope.internet.subtype = "Coroa";
						$scope.disableSubtype = true;
						$scope.internet.material = "Banho de Ouro";
						$scope.disableMaterial = true;
					break;

				}

				break;

			case "Presépios":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				break;

			case "Batismo e Nascimento":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				break;

			case "Anjos":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				break;

			case "Divinos de Parede":
				$scope.enableMaterial = true;
				$scope.enablePainting = true;
				break;

			case "Adornos":
				$scope.enableSubtype = true;
				$scope.enableFigure = true;
				$scope.enableMaterial = true;
				break;

			case "Crucifixos":
				$scope.enableSubtype = true;
				$scope.enableMaterial = true;
				break;

			case "Arte Sacra":
				$scope.enableSubtype = true;
				$scope.enableFigure = true;
				$scope.enableMaterial = true;
				$scope.enablePainting = true;
				$scope.enableStyle = true;
				break;

			case "Quadros":
				$scope.enableFigure = true;
				break;

			default:
			break;

		}

	}

	// Change fields per subtype
	$scope.subtypeSelected = function () {

		switch ($scope.internet.subtype) {

			case "Porta Água Benta":
				$scope.enableFigure = true;
				$scope.disableSubtype = true;
				break;

			case "Medalhão de Berço":
				$scope.internet.baseFigure = "Anjo";
				$scope.enableFigure = true;
				$scope.disableBaseFigure = true;
				$scope.disableSubtype = true;
				break;

			case "Presépio":
				$scope.enableStyle = true;
				$scope.disableSubtype = true;
				$scope.titleTemplate = "Subtype+Material+Other";
				break;

			case "Adorno":
				$scope.enableFigure = true;
				$scope.titleTemplate = "Subtype+Figure";
				$scope.disableSubtype = true;
				break;

			case "Cruz":
				$scope.titleTemplate = "Subtype+Material+Other";
				break;

			case "Corrente":
				$scope.enableFigure = false;
				$scope.enableShape = false;
				$scope.titleTemplate = "Subtype+Material+Other";
				break;

			case "Peças de Presépio":
				$scope.titleTemplate = "open descrition";
				break;

			case "Menino Jesus":
				$scope.titleTemplate = "Subtype+Material+Other";

		}

	}

	// Work with the variant info
	function setVariantInfo(variantInfo) {

		var sizeInfo;
		var keys = Object.keys(variantInfo);

		// Set products with only one variant
		if (keys.length == 1) {

			switch (variantInfo[0].sizetype) {
				case "1D":
					sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
					break;
				case "2D":
					sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " X " + variantInfo[0].size3.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
					break;
				case "3D":
					sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " X " + variantInfo[0].size3.toString().replace('.',',') + " X " + variantInfo[0].size4.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
					break;
			}

			$scope.shopify.details += sizeInfo;

			if (variantInfo[0].color) {
				 $scope.shopify.tags += ", "; $scope.shopify.tags += variantInfo[0].color;
			}
			
		}

		// Set products with more than one variant
		else {

			if ($scope.product.model == 'Tamanho') {

				var sizeArray = [];
				var i; var x; var y;

				// Get all sizes
				for (i in variantInfo) {

					switch (variantInfo[i].sizetype) {
						case "1D":
							sizeArray.push(variantInfo[i].size1.toString().replace('.',','));
							break;
						case "2D":
							var addSize = variantInfo[i].size1.toString().replace('.',',') + " x " + variantInfo[i].size3.toString().replace('.',',');
							sizeArray.push(addSize);
							break;
						case "3D":
							var addSize = variantInfo[i].size1.toString().replace('.',',') + " x " + variantInfo[i].size3.toString().replace('.',',') + " x " + variantInfo[i].size4.toString().replace('.',',');
							sizeArray.push(addSize);
							break;

					}

				}

				// Sort in numerical order
				if (variantInfo[0].sizetype == "1D") {
					sizeArray.sort(function(a, b){return a-b});
				}

				// Built size
				var sizeText = "<li>Tamanhos: ";

				for (x in sizeArray) {
					sizeText += sizeArray[x];
					sizeText += " "; sizeText += variantInfo[0].size2; sizeText += ", ";
				}

				sizeText = sizeText.substring(0, sizeText.length - 2);
				sizeText += "</li>";

				console.log(sizeText);
				$scope.shopify.details += sizeText;

			}

			else {

				switch (variantInfo[0].sizetype) {
					case "1D":
						sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
						break;
					case "2D":
						sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " X " + variantInfo[0].size3.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
						break;
					case "3D":
						sizeInfo = "<li>Tamanho: " + variantInfo[0].size1.toString().replace('.',',') + " X " + variantInfo[0].size3.toString().replace('.',',') + " X " + variantInfo[0].size4.toString().replace('.',',') + " " + variantInfo[0].size2 + "</li>";
						break;
				}

				$scope.shopify.details += sizeInfo;

				if ($scope.product.model == 'Cor') {

					var colorText = "<li>Cores: ";

					for (y in variantInfo) {
						colorText += variantInfo[y].color;
						colorText += ", "
					}

					colorText = colorText.substring(0, colorText.length - 2);
					colorText += "</li>";

					$scope.shopify.details += colorText;

				}
			
			}
			
		}

		var i;
		for (i in variantInfo) {
			console.log(variantInfo[i]);
		}

	}

	// To click in "Próximo" button, "Produto" tab
	$scope.nextButton = function () {
		setShopify();
		$scope.activeTab += 1;
		$window.scrollTo(0, 0);
		setVariantInfo($scope.internet.variant);

		$scope.shopifyTab = true;

	}

	// To click in "Próximo" button, "Shopify" tab
	$scope.nextButton2 = function () {
		$scope.activeTab += 1;
		$window.scrollTo(0, 0);

		$scope.googleTab = true;

		$scope.google.title = $scope.shopify.title;

	}

	// Disable when click in tab
	$scope.clickTab = function () {
		console.log("Click Tab");
	}

	// To set all Shopify fields before submit
	function setShopify() {

		//To create the Shopify details and TAG sentences
		var draftDetails = ""; var draftTags = ""; var draftCribs = "<li>Inclui ";

		switch ($scope.titleTemplate) {

			case "Type+Figure+Painting":
				$scope.shopify.title = "Imagem de " + $scope.internet.figure;
				if ($scope.internet.painting != "Colorida") {
					$scope.shopify.title += " ";
					$scope.shopify.title += $scope.internet.painting;
				}
			break;

			case "Type+Figure+Material":
				$scope.shopify.title = "Imagem de " + $scope.internet.figure + " de " + $scope.internet.material;
			break;

			case "Type+Material+Other":

				$scope.shopify.title = $scope.product.type;

				if ($scope.enableFigure) {
					$scope.shopify.title += " de "
					$scope.shopify.title += $scope.internet.figure;
				}

				$scope.shopify.title += " de ";
				$scope.shopify.title += $scope.internet.material;

				if ($scope.accessory.clasp) {
					$scope.shopify.title += " com Fecho";
				}

			break;

			case "Subtype+Material+Other":
				$scope.shopify.title = $scope.internet.subtype;
				if ($scope.product.type=="Terços" & $scope.internet.country!=="undefined") {
					$scope.shopify.title += " " + $scope.internet.country;
				}
				$scope.shopify.title += " de " + $scope.internet.material;

				if ($scope.product.type=="Terços") {
					var keys = Object.keys($scope.internet.variant);
					if (keys.length == 1) {
						$scope.shopify.title += " " + $scope.internet.variant[0].color;
					}
				}

				if ($scope.internet.material == "Hematita" & $scope.internet.shape != "Redondo") {
					$scope.shopify.title += " "; $scope.shopify.title += $scope.internet.shape;
				}

				if ($scope.accessory.zirconia) {
					$scope.shopify.title += " e Zircônia";
				}

				if ($scope.special.style) {
					$scope.shopify.title += " "
					$scope.shopify.title += $scope.special.style;
				}

			break;

			case "Subtype+Figure":
				$scope.shopify.title = $scope.internet.subtype + " " + $scope.internet.figure;
			break;

			case "open descrition":
				$scope.titleDisable = false;
				$scope.shopify.title = "Escrever o descritivo aqui!";

			break;

			default:

				if ($scope.product.type == "Imagens") {

					$scope.shopify.title = "Imagem de " + $scope.internet.figure + " de " + $scope.internet.material;

				}

				else {

					$scope.shopify.title = $scope.internet.subtype + " de " + $scope.internet.figure + " de " + $scope.internet.material;
//					$scope.shopify.title = "Template de título não definido";

				}

			break;

		}

		// IMPORTANT: To configure Shopify description details list

		if ($scope.event.neck) { draftDetails += '<li>Pode ser usado no Pescoço</li>'; }
		if ($scope.event.car) { draftDetails += '<li>Pode ser usado no Carro</li>'; }

		if ($scope.enableMaterial) {
			if ($scope.product.type == 'Terços') {
				draftDetails += '<li>Material da Conta: ' + $scope.internet.material + '</li>';
			} else {
				draftDetails += '<li>Material: ' + $scope.internet.material + '</li>';
			}
		}

		if ($scope.enableChainMaterial) { draftDetails += '<li>Material da Corrente: ' + $scope.internet.chainMaterial + '</li>'; }
		if ($scope.enableMedal) { draftDetails += '<li>Medalha (entremeio): ' + $scope.internet.medal + '</li>'; }

		if ($scope.enablePainting & $scope.internet.painting != 'Sem pintura') { draftDetails += '<li>Pintura: ' + $scope.internet.painting + '</li>'; }

		if ($scope.appeal.external) { draftDetails += '<li>Próprio para Ambiente Externo</li>'; }

		if ($scope.accessory.glassEye) { draftDetails += '<li>Com Olhos de Vidro</li>'; }
		if ($scope.accessory.lamp) { draftDetails += '<li>Com Abajur</li>'; }
		if ($scope.accessory.zirconia) { draftDetails += '<li>Com Zircônia</li>'; }
		if ($scope.accessory.swarovski) { draftDetails += '<li>Com Swarovski</li>'; }
		if ($scope.accessory.clasp) { draftDetails += '<li>Com Fecho</li>'; }
		if ($scope.accessory.rhinestones) { draftDetails += '<li>Com Strass</li>'; }
		if ($scope.accessory.niche) { draftDetails += '<li>Já inclui o Nicho</li>'; }
		if ($scope.accessory.halo) { draftDetails += '<li>Inclui Auréola</li>'; }
		if ($scope.accessory.resplendor) { draftDetails += '<li>Inclui Resplendor</li>'; }
		if ($scope.accessory.crown) { draftDetails += '<li>Inclui Coroa</li>'; }
		if ($scope.accessory.candles) { draftDetails += '<li>Inclui Velas</li>'; }
		if ($scope.accessory.chain) { draftDetails += '<li>Inclui Corrente</li>'; }
		if ($scope.accessory.bronze) { draftDetails += '<li>Acabamento em Bronze</li>'; }


		if ($scope.internet.country == 'Nacional') { draftDetails += '<li>Produzido no Brasil</li>'; }
		if ($scope.internet.country == 'Italiano') { draftDetails += '<li>Produto Italiano</li>'; }

		if ($scope.internet.chainSize) { draftDetails += '<li>Tamanho da Corrente: ' + $scope.internet.chainSize + ' cm</li>'; }
		if ($scope.internet.forImage3) { draftDetails += '<li>Sugerimos para Imagens de ' + $scope.internet.forImage1 + ' até ' + $scope.internet.forImage2 + ' ' + $scope.internet.forImage3 + '</li>'; }
		if ($scope.internet.forRosaries) { draftDetails += '<li>Para Terços de até: ' + $scope.internet.forRosaries + ' cm</li>'; }

		if ($scope.enableStoneSize) { draftDetails += '<li>Tamanho da Conta: ' + $scope.internet.stoneSize + ' mm</li>'; }

		if ($scope.crib.jesus) { draftCribs += 'Menino Jesus na manjedoura, ' };
		if ($scope.crib.mary) { draftCribs += 'Maria, ' };
		if ($scope.crib.joseph) { draftCribs += 'José, ' };
		if ($scope.crib.melchior && $scope.crib.baltasar && $scope.crib.gaspar) { draftCribs += '3 Reis Magos, ' };
		if ($scope.crib.angel) { draftCribs += 'Anjo da Glória, ' };
		if ($scope.crib.cow) { draftCribs += 'Vaca, ' };
		if ($scope.crib.horse) { draftCribs += 'Cavalo, ' };
		if ($scope.crib.shepherd) { draftCribs += 'Pastor, ' };
		if ($scope.crib.sheep) { draftCribs += 'Ovelha, ' };
		if ($scope.crib.chicken) { draftCribs += 'Galinha, ' };
		if ($scope.crib.camel) { draftCribs += 'Camelo, ' };
		draftCribs = draftCribs.substring(0, draftCribs.length - 2);
		draftCribs += '</li>';
		if ($scope.internet.subtype == 'Presépio') { draftDetails += draftCribs; }

		if ($scope.crib.house) { draftDetails += '<li>Inclui casinha</li>' };
		if ($scope.internet.parts) { draftDetails += '<li>' + $scope.internet.parts + ' peças</li>' };

		if ($scope.internet.forCribs) { draftDetails += '<li>Para Presépios de ' + $scope.internet.forCribs + ' cm</li>'; }

		// IMPORTANT: To configure Shopify TAGS

		if ($scope.product.type == 'Imagens') { draftTags += 'Imagem, '; }
		if ($scope.product.type == 'Terços') { draftTags += 'Terço, '; }
		if ($scope.product.type == 'Jóias') { draftTags += 'Jóia, '; }
		if ($scope.product.type == 'Escapulários') { draftTags += 'Escapulário, '; }
		if ($scope.product.type == 'Presépios') { draftTags += 'Presépio, '; }
		if ($scope.product.type == 'Crucifixos') { draftTags += 'Crucifixo, '; }
		if ($scope.product.type == 'Adornos') { draftTags += 'Adorno, '; }
		if ($scope.product.type == 'Quadros') { draftTags += 'Quadro, '; }
		if ($scope.product.type == 'Porta Santo') { draftTags += 'Porta Santo, '; }
		if ($scope.product.type == 'Porta Terço') { draftTags += 'Porta Terço, '; }

		if ($scope.internet.subtype) { draftTags += $scope.internet.subtype; draftTags += ", "; }
		if ($scope.internet.subtype) { draftTags += "Subcategoria_"; draftTags += $scope.internet.subtype; draftTags += ", "; }

		if ($scope.enableMaterial) { draftTags += $scope.internet.material; draftTags += ', '; }
		if ($scope.enableMaterial & $scope.product.type == 'Imagens') { draftTags += 'Material_'; draftTags += $scope.internet.material; draftTags += ', '; }
		if ($scope.enableMaterial & $scope.product.type == 'Terços') { draftTags += 'Material_'; draftTags += $scope.internet.material; draftTags += ', '; }
		if ($scope.enableMaterial & $scope.product.type == 'Jóias') { draftTags += 'Material_'; draftTags += $scope.internet.material; draftTags += ', '; }

		if ($scope.internet.baseFigure == 'Nossa Senhora') { draftTags += 'Nossa Senhora, '; }
		if ($scope.internet.baseFigure == 'Santa') { draftTags += 'Santa, '; }
		if ($scope.internet.baseFigure == 'Jesus') { draftTags += 'Jesus, '; }

//		if ($scope.internet.figure == 'Imaculado Coração de Maria') { draftTags += 'Imaculado Coração de Maria, '; }
//		if ($scope.internet.figure == 'Santa Rita de Cássia') { draftTags += 'Santa Rita, '; }


		if ($scope.internet.figure == 'Nossa Senhora das Graças') { draftTags += 'Nossa Senhora das Graças, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Fátima') { draftTags += 'Nossa Senhora de Fátima, '; }
		if ($scope.internet.figure == 'Nossa Senhora Aparecida') { draftTags += 'Nossa Senhora Aparecida, '; }
		if ($scope.internet.figure == 'Imaculado Coração de Maria') { draftTags += 'Imaculado Coração de Maria, '; }
		if ($scope.internet.figure == 'Madona') { draftTags += 'Madona, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Lourdes') { draftTags += 'Nossa Senhora de Lourdes, '; }
		if ($scope.internet.figure == 'Nossa Senhora Grávida') { draftTags += 'Nossa Senhora Grávida, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Guadalupe') { draftTags += 'Nossa Senhora de Guadalupe, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Carmo') { draftTags += 'Nossa Senhora do Carmo, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Desterro') { draftTags += 'Nossa Senhora do Desterro, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Nazaré') { draftTags += 'Nossa Senhora de Nazaré, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Međugorje') { draftTags += 'Nossa Senhora de Međugorje, '; }
		if ($scope.internet.figure == 'Nossa Senhora de Schoenstatt') { draftTags += 'Nossa Senhora de Schoenstatt, '; }
		if ($scope.internet.figure == 'Nossa Senhora Auxiliadora') { draftTags += 'Nossa Senhora Auxiliadora, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Assunção') { draftTags += 'Nossa Senhora da Assunção, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Boa Viagem') { draftTags += 'Nossa Senhora da Boa Viagem, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Conceição') { draftTags += 'Nossa Senhora da Conceição, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Perpétuo Socorro') { draftTags += 'Nossa Senhora do Perpétuo Socorro, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Confiança') { draftTags += 'Nossa Senhora da Confiança, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Divina Providência') { draftTags += 'Nossa Senhora da Divina Providência, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Guia') { draftTags += 'Nossa Senhora da Guia, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Rosa Mística') { draftTags += 'Nossa Senhora da Rosa Mística, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Sabedoria') { draftTags += 'Nossa Senhora da Sabedoria, '; }
		if ($scope.internet.figure == 'Nossa Senhora da Saúde') { draftTags += 'Nossa Senhora da Saúde, '; }
		if ($scope.internet.figure == 'Nossa Senhora das Cabeças') { draftTags += 'Nossa Senhora das Cabeças, '; }
		if ($scope.internet.figure == 'Nossa Senhora das Dores') { draftTags += 'Nossa Senhora das Dores, '; }
		if ($scope.internet.figure == 'Nossa Senhora Desatadora dos Nós') { draftTags += 'Nossa Senhora Desatadora dos Nós, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Bom Parto') { draftTags += 'Nossa Senhora do Bom Parto, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Leite') { draftTags += 'Nossa Senhora do Leite, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Perpétuo Socorro') { draftTags += 'Nossa Senhora do Perpétuo Socorro, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Rosário') { draftTags += 'Nossa Senhora do Rosário, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Socorro') { draftTags += 'Nossa Senhora do Socorro, '; }
		if ($scope.internet.figure == 'Nossa Senhora dos Navegantes') { draftTags += 'Nossa Senhora dos Navegantes, '; }
		if ($scope.internet.figure == 'Nossa Senhora do Sorriso') { draftTags += 'Nossa Senhora do Sorriso, '; }
		if ($scope.internet.figure == 'Nossa Senhora Mãe Amabilíssima') { draftTags += 'Nossa Senhora Mãe Amabilíssima, '; }
		if ($scope.internet.figure == 'Nossa Senhora Mãe de Deus') { draftTags += 'Nossa Senhora Mãe de Deus, '; }
		if ($scope.internet.figure == 'Pietà') { draftTags += 'Pietà, '; }
		if ($scope.internet.figure == 'Jesus') { draftTags += 'Jesus, '; }
		if ($scope.internet.figure == 'Sagrado Coração de Jesus') { draftTags += 'Sagrado Coração de Jesus, '; }
		if ($scope.internet.figure == 'Jesus Misericordioso') { draftTags += 'Jesus Misericordioso, '; }
		if ($scope.internet.figure == 'Menino Jesus de Praga') { draftTags += 'Menino Jesus de Praga, '; }
		if ($scope.internet.figure == 'Jesus Ressuscitado') { draftTags += 'Jesus Ressuscitado, '; }
		if ($scope.internet.figure == 'Face de Cristo') { draftTags += 'Face de Cristo, '; }
		if ($scope.internet.figure == 'Jesus Orando') { draftTags += 'Jesus Orando, '; }
		if ($scope.internet.figure == 'Bom Pastor') { draftTags += 'Bom Pastor, '; }
		if ($scope.internet.figure == 'Divino Menino Jesus') { draftTags += 'Divino Menino Jesus, '; }
		if ($scope.internet.figure == 'Jesus Nazareno') { draftTags += 'Jesus Nazareno, '; }
		if ($scope.internet.figure == 'Bom Jesus') { draftTags += 'Bom Jesus, '; }
		if ($scope.internet.figure == 'Santa Rita de Cássia') { draftTags += 'Santa Rita de Cássia, '; }
		if ($scope.internet.figure == 'Santa Terezinha') { draftTags += 'Santa Terezinha, '; }
		if ($scope.internet.figure == 'Santa Bárbara') { draftTags += 'Santa Bárbara, '; }
		if ($scope.internet.figure == 'Santa Cecília') { draftTags += 'Santa Cecília, '; }
		if ($scope.internet.figure == 'Santa Clara') { draftTags += 'Santa Clara, '; }
		if ($scope.internet.figure == 'Santa Edwiges') { draftTags += 'Santa Edwiges, '; }
		if ($scope.internet.figure == 'Santa Luzia') { draftTags += 'Santa Luzia, '; }
		if ($scope.internet.figure == 'Santa Ana') { draftTags += 'Santa Ana, '; }
		if ($scope.internet.figure == 'Santa Catarina') { draftTags += 'Santa Catarina, '; }
		if ($scope.internet.figure == 'Santa Helena') { draftTags += 'Santa Helena, '; }
		if ($scope.internet.figure == 'Santa Apolônia') { draftTags += 'Santa Apolônia, '; }
		if ($scope.internet.figure == 'Santa Filomena') { draftTags += 'Santa Filomena, '; }
		if ($scope.internet.figure == 'Santa Ágata') { draftTags += 'Santa Ágata, '; }
		if ($scope.internet.figure == 'Santa Laura') { draftTags += 'Santa Laura, '; }
		if ($scope.internet.figure == 'Santa Madre Paulina') { draftTags += 'Santa Madre Paulina, '; }
		if ($scope.internet.figure == 'Santa Marta') { draftTags += 'Santa Marta, '; }
		if ($scope.internet.figure == 'Santa Teresa de Ávila') { draftTags += 'Santa Teresa de Ávila, '; }
		if ($scope.internet.figure == 'Santa Ifigênia') { draftTags += 'Santa Ifigênia, '; }
		if ($scope.internet.figure == 'Santa Beatriz') { draftTags += 'Santa Beatriz, '; }
		if ($scope.internet.figure == "Santa Joana d'Arc") { draftTags += "Santa Joana d'Arc, "; }
		if ($scope.internet.figure == 'Santa Isabel') { draftTags += 'Santa Isabel, '; }
		if ($scope.internet.figure == 'Santa Faustina') { draftTags += 'Santa Faustina, '; }
		if ($scope.internet.figure == 'São Francisco de Assis') { draftTags += 'São Francisco de Assis, '; }
		if ($scope.internet.figure == 'Santo Expedito') { draftTags += 'Santo Expedito, '; }
		if ($scope.internet.figure == 'Santo Antônio') { draftTags += 'Santo Antônio, '; }
		if ($scope.internet.figure == 'São Bento') { draftTags += 'São Bento, '; }
		if ($scope.internet.figure == 'São Jorge') { draftTags += 'São Jorge, '; }
		if ($scope.internet.figure == 'São José') { draftTags += 'São José, '; }
		if ($scope.internet.figure == 'São Paulo') { draftTags += 'São Paulo, '; }
		if ($scope.internet.figure == 'São Pedro') { draftTags += 'São Pedro, '; }
		if ($scope.internet.figure == 'Padre Pio') { draftTags += 'Padre Pio, '; }
		if ($scope.internet.figure == 'São João') { draftTags += 'São João, '; }
		if ($scope.internet.figure == 'São João Batista') { draftTags += 'São João Batista, '; }
		if ($scope.internet.figure == 'São Judas Tadeu') { draftTags += 'São Judas Tadeu, '; }
		if ($scope.internet.figure == 'Frei Galvão') { draftTags += 'Frei Galvão, '; }
		if ($scope.internet.figure == 'Santo Afonso') { draftTags += 'Santo Afonso, '; }
		if ($scope.internet.figure == 'Santo Amaro') { draftTags += 'Santo Amaro, '; }
		if ($scope.internet.figure == 'Santo André') { draftTags += 'Santo André, '; }
		if ($scope.internet.figure == 'Santo Agostinho') { draftTags += 'Santo Agostinho, '; }
		if ($scope.internet.figure == 'Santo Ivo') { draftTags += 'Santo Ivo, '; }
		if ($scope.internet.figure == 'São Benedito') { draftTags += 'São Benedito, '; }
		if ($scope.internet.figure == 'São Brás') { draftTags += 'São Brás, '; }
		if ($scope.internet.figure == 'São Cosme e Damião') { draftTags += 'São Cosme e Damião, '; }
		if ($scope.internet.figure == 'São Cristóvão') { draftTags += 'São Cristóvão, '; }
		if ($scope.internet.figure == 'São Damião') { draftTags += 'São Damião, '; }
		if ($scope.internet.figure == 'São Geraldo') { draftTags += 'São Geraldo, '; }
		if ($scope.internet.figure == 'São Jerônimo') { draftTags += 'São Jerônimo, '; }
		if ($scope.internet.figure == 'São Lázaro') { draftTags += 'São Lázaro, '; }
		if ($scope.internet.figure == 'São Longuinho') { draftTags += 'São Longuinho, '; }
		if ($scope.internet.figure == 'São Lourenço') { draftTags += 'São Lourenço, '; }
		if ($scope.internet.figure == 'São Lucas') { draftTags += 'São Lucas, '; }
		if ($scope.internet.figure == 'São Martinho de Porres') { draftTags += 'São Martinho de Porres, '; }
		if ($scope.internet.figure == 'São Mateus') { draftTags += 'São Mateus, '; }
		if ($scope.internet.figure == 'São Nicolau') { draftTags += 'São Nicolau, '; }
		if ($scope.internet.figure == 'São Pelegrino') { draftTags += 'São Pelegrino, '; }
		if ($scope.internet.figure == 'São Roque') { draftTags += 'São Roque, '; }
		if ($scope.internet.figure == 'São Sebastião') { draftTags += 'São Sebastião, '; }
		if ($scope.internet.figure == 'São Miguel Arcanjo') { draftTags += 'São Miguel Arcanjo, '; }
		if ($scope.internet.figure == 'São Gabriel Arcanjo') { draftTags += 'São Gabriel Arcanjo, '; }
		if ($scope.internet.figure == 'São Rafael') { draftTags += 'São Rafael, '; }
		if ($scope.internet.figure == 'Anjo da Guarda') { draftTags += 'Anjo da Guarda, '; }
		if ($scope.internet.figure == 'Arcanjo') { draftTags += 'Arcanjo, '; }
		if ($scope.internet.figure == 'Anjo') { draftTags += 'Anjo, '; }
		if ($scope.internet.figure == 'Sagrada Família') { draftTags += 'Sagrada Família, '; }
		if ($scope.internet.figure == 'Divino Espirito Santo') { draftTags += 'Divino Espirito Santo, '; }
		if ($scope.internet.figure == 'Cruz') { draftTags += 'Cruz, '; }
		if ($scope.internet.figure == 'Santa Ceia') { draftTags += 'Santa Ceia, '; }
		if ($scope.internet.figure == 'Cálice de Primeira Comunhão') { draftTags += 'Cálice de Primeira Comunhão, '; }
		if ($scope.internet.figure == 'Divino Pai Eterno') { draftTags += 'Divino Pai Eterno, '; }
		if ($scope.internet.figure == 'Pai Nosso') { draftTags += 'Pai Nosso, '; }
		if ($scope.internet.figure == '10 Mandamentos') { draftTags += '10 Mandamentos, '; }
		if ($scope.internet.figure == 'Pombos') { draftTags += 'Pombos, '; }


		if ($scope.internet.figure == 'Imaculado Coração de Maria' & $scope.product.type == 'Imagens') { draftTags += 'Figura_Imaculado Coração de Maria, '; }
		if ($scope.internet.figure == 'Santa Rita de Cássia' & $scope.product.type == 'Imagens') { draftTags += 'Figura_Santa Rita, '; }

		if ($scope.internet.medal == 'Nossa Senhora') { draftTags += 'Nossa Senhora, '; }
		if ($scope.internet.medal == 'Nossa Senhora de Fátima') { draftTags += 'Nossa Senhora de Fátima, '; }
		if ($scope.internet.medal == 'Nossa Senhora das Graças') { draftTags += 'Nossa Senhora das Graças, '; }

		if ($scope.internet.country == 'Nacional') { draftTags += 'Nacional, '; }
		if ($scope.internet.country == 'Italiano') { draftTags += 'Italiano, '; }

		if ($scope.product.supplier == 'PEMA') { draftTags += 'Arte Sacra, '; }

		if ($scope.appeal.gift) { draftTags += 'Presente, '; }
		if ($scope.appeal.souvenir) { draftTags += 'Lembrancinha, '; }
		if ($scope.appeal.decor) { draftTags += 'Decoração, '; }
		if ($scope.appeal.protection) { draftTags += 'Proteção, '; }
		if ($scope.appeal.external) { draftTags += 'Ambiente Externo, '; }

		if ($scope.event.christmas) { draftTags += 'Natal, '; }
		if ($scope.event.firstCommunion) { draftTags += 'Primeira Comunhão, '; }
		if ($scope.event.baptism) { draftTags += 'Batismo, '; }
		if ($scope.event.birth) { draftTags += 'Nascimento, '; }
		if ($scope.event.mothersGift) { draftTags += 'Para Mães, '; }
		if ($scope.event.fathersGift) { draftTags += 'Para Pais, '; }
		if ($scope.event.kids) { draftTags += 'Para Crianças, '; }
		if ($scope.event.chrism) { draftTags += 'Crisma, '; }
		if ($scope.event.marriage) { draftTags += 'Casamento, '; }
		if ($scope.event.jubilee) { draftTags += 'Bodas, '; }
		if ($scope.event.godfather) { draftTags += 'Para Padrinhos, '; }
		if ($scope.event.invited) { draftTags += 'Para Convidados, '; }
		if ($scope.event.neck) { draftTags += 'Para Pescoço, '; }
		if ($scope.event.car) { draftTags += 'Para Carro, '; }
		if ($scope.event.bride) { draftTags += 'Para Noiva, '; }

		$scope.shopify.details += draftDetails;
		$scope.shopify.tags += draftTags.substring(0, draftTags.length - 2);

	}

	// To build variable name to include in variable field
	$scope.variableName = function (model,variant) {
		if (model && variant) {
			return model + " - " + variant;
		}
		else {
			return "";
		}
	}

	// To open to select figure
	$scope.openFigure = function () {
		$scope.enableFigure = true;
	}

	// To open tp select figure or style
	$scope.openRadio = function () {

		if ($scope.aux.type == 'Figura') { $scope.enableFigure = true; $scope.enableStyle = false; }
		if ($scope.aux.type == 'Estilo') { $scope.enableStyle = true; $scope.enableFigure = false; }

	}

	getNewProdList();

});