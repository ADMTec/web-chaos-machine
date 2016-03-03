angular.module("webcm.services").factory("LicenseService", ["ApiService",
	function(ApiService) {
		"use strict";

		var service = {};

		// register license
		service.register = function(form) {
			return ApiService.post("/license/register", form);
		};

		return service;
	}
]);