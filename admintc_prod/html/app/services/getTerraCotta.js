'use strict';
/**
* Description
*  	 getTerraCotta Service
*/

app.service('getTerraCotta', function($http){

	this.types = function () {

		return $http({
			url: '../helper/get_types',
			method: 'GET',
		});

	}

	this.models = function () {

		return $http({
			url: '../helper/get_models',
			method: 'GET',
		});

	}

	this.suppliers = function () {

		return $http({
			url: '../helper/get_suppliers',
			method: 'GET',
		});

	}

});