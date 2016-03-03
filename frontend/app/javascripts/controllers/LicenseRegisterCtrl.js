angular.module("webcm.controllers").controller("LicenseRegisterCtrl", ["$scope", "$state", "$timeout", "LicenseService",
	function($scope, $state, $timeout, LicenseService) {
		"use strict";

		$scope.form = {
			address: window.location.origin
		};

		$scope.response = {};

		$scope.licenseTypeList = [
			{ type: 0, name: "Free" },
			{ type: 1, name: "Premium" }
		];

		$scope.selectLicenseType = function(option) {
			$scope.form.licenseType = option.type;
		};

		$scope.submit = function() {
			LicenseService.register($scope.form)
				.then(function(data) {
					$scope.response = data;
					if($scope.response.code === "LICENSE_REGISTER_SUCCESS") {
						$timeout(function() {
							$state.go("machine.authentication.signin");
						}, 3000);
					}
				});
		};
	}
]);