angular.module("webcm.services").factory("MachineService", ["ApiService",
	function(ApiService) {
		"use strict";

		var service = {};

		// machine configs
		service.config = function() {
			return ApiService.get("/machine/config");
		};

		// get mixes
		service.getMixes = function() {
			return ApiService.get("/machine/getMixes");
		};

		// check mix status
		service.checkMix = function(executeMix, character, currentMix, items) {
			return ApiService.post("/machine/checkMix", {
				"executeMix": executeMix,
				"character": character,
				"currentMix": currentMix,
				"items": items
			});
		};

		return service;
	}
]);