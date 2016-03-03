/**
 * App Config
 */
angular.module("webcm").config(["$stateProvider", "$urlRouterProvider", "$httpProvider", "$translateProvider", "lockerProvider",
	function($stateProvider, $urlRouterProvider, $httpProvider, $translateProvider, lockerProvider) {
		"use strict";

		$httpProvider.interceptors.push("HttpInterceptorFactory");

		$translateProvider.useStaticFilesLoader({
		    prefix: "locales/",
		    suffix: ".json"
		  });
		//Default Language
		$translateProvider.preferredLanguage("pt-BR");

		lockerProvider.setDefaultDriver("session")
			.setDefaultNamespace("webcm")
			.setSeparator(".")
			.setEventsEnabled(false);

		$urlRouterProvider.otherwise(function() {
			if (window.sessionStorage["webcm.session"] === undefined) {
				return "/machine/authentication/signin";
			} else {
				return "/machine/selectCharacter";
			}
		});

		$stateProvider
			.state("machine", {
				authorize: false,
				abstract: true,
				url: "/machine",
				template: "<div ui-view></div>"
			})
			.state("machine.authentication", {
				authorize: false,
				abstract: true,
				url: "/authentication",
				template: "<div ui-view></div>"
			})
			.state("machine.authentication.signin", {
				authorize: false,
				abstract: false,
				url: "/signin",
				templateUrl: "./views/authenticationSignIn.html",
				controller: "AuthenticationSignInCtrl"
			})
			.state("machine.authentication.signout", {
				authorize: true,
				abstract: false,
				url: "/signout",
				controller: "AuthenticationSignOutCtrl"
			})
			.state("machine.selectCharacter", {
				authorize: true,
				abstract: false,
				url: "/selectCharacter",
				templateUrl: "./views/selectCharacter.html",
				controller: "SelectCharacterCtrl"
			})
			.state("machine.license", {
				authorize: false,
				abstract: true,
				url: "/license",
				template: "<div ui-view></div>"
			})
			.state("machine.license.register", {
				authorize: false,
				abstract: false,
				url: "/register",
				templateUrl: "./views/licenseRegister.html",
				controller: "LicenseRegisterCtrl"
			});
	}
]);

/**
 * App Run
 */
angular.module("webcm").run(["$rootScope", "$location", "$window", "$state", "locker", "MachineService", 
	function($rootScope, $location, $window, $state, locker, MachineService) {
		"use strict";

		MachineService.config()
			.then(function(data) {
				$rootScope.theme = data.template;
				if(data.have_license === false) {
					$state.go("machine.license.register");
				}
			});

		//Faz o hook no change do state
		$rootScope.$on("$stateChangeStart", function(event, toState /*, toParams, fromState, fromParams*/ ) {
			// transitionTo() promise will be rejected with
			// a 'transition prevented' error

			var session = locker.get("session");
			if (toState.authorize === true && session === undefined) {
				event.preventDefault();
				$state.go("machine.authentication.signin");
			}
		});
	}
]);