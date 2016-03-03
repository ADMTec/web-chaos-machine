angular.module("webcm.services").factory("AuthenticationService", ["ApiService",
	function(ApiService) {
		"use strict";

		var service = {};

		service.signIn = function(form) {
			return ApiService.post("/authentication/signin", form);
		};

		service.signOut = function() {
			return ApiService.get("/authentication/signout");
		};

		return service;
	}
]);