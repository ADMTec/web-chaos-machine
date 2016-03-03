angular.module("webcm.controllers").controller("AuthenticationSignOutCtrl", ["$scope", "$state", "locker", "AuthenticationService",
	function($scope, $state, locker, AuthenticationService) {
		"use strict";

		AuthenticationService.signOut();
		locker.forget("session");
		$state.go("machine.authentication.signin");
	}
]);