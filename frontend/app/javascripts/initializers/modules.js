(function() {

	"use strict";

	/**
	 * Services module
	 */
	angular.module("webcm.services", []);

	/**
	 * Controllers module
	 */
	angular.module("webcm.controllers", [
		"webcm.services",
		"ui.router"
	]);

	/**
	 * Helpers modole
	 */
	angular.module("webcm.helpers", []);

	/**
	 * Filters module
	 */
	angular.module("webcm.filters", []);

	/**
	 * Directives module
	 */
	angular.module("webcm.directives", []);

	/**
	 * Application module
	 */
	angular.module("webcm", [
		//app modules
		"webcm.services",
		"webcm.controllers",
		"webcm.filters",
		"webcm.directives",
		"webcm.helpers",

		//3rd-party modules
		"ui.router",
		"angular-locker",
		"pascalprecht.translate"
	]);

})();