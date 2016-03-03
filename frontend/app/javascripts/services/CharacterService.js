angular.module("webcm.services").factory("CharacterService", ["ApiService",
	function(ApiService) {
		"use strict";

		var service = {};

		// get character list
		service.getCharacterList = function() {
			return ApiService.post("/character/getCharacterList");
		};

		// get character items
		service.getCharacterItems = function(character) {
			return ApiService.post(
				"/character/getCharacterItems", {
					"character": character
				}
			);
		};

		return service;
	}
]);