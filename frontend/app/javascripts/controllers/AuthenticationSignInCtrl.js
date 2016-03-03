angular.module("webcm.controllers").controller("AuthenticationSignInCtrl", ["$scope", "$state", "locker", "AuthenticationService",
	function($scope, $state, locker, AuthenticationService) {
		"use strict";

		$scope.form = {};

		$scope.response = {};

		$scope.submit = function() {
			AuthenticationService.signIn($scope.form)
				.then(function(data) {
					$scope.response = data;

					if ($scope.response.auth) {
						locker.put("session", true);
						$state.go("machine.selectCharacter");
					}
				});
		};

	}
]);