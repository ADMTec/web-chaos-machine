angular.module("webcm.controllers").controller("SelectCharacterCtrl", ["$scope", "$state", "MachineService", "CharacterService",
	function($scope, $state, MachineService, CharacterService) {
		"use strict";

		$scope.selectedCharacter = null;
		$scope.characterList = [];

		$scope.selectedMix = null;
		$scope.mixList = [];

		$scope.characterItems = [];

		$scope.mixButtonState = false;

		$scope.machineAlert = null;

		// callback on select mix
		$scope.selectMix = function(mix) {
			$scope.selectedMix = mix;
			$scope.checkMix();
		};

		// get character items
		$scope.getCharacterItems = function() {
			$scope.characterItems = []; //reset items
			CharacterService.getCharacterItems($scope.selectedCharacter.name)
				.then(function(data) {
					angular.forEach(data, function(item) {
						item.intoMachine = false;
						$scope.characterItems.push(item);
					});
				});
		};

		// callback on select character
		$scope.selectCharacter = function(character) {
			if (character.name.length > 0) {
				$scope.selectedCharacter = character;
				$scope.getCharacterItems();
			}
		};

		// item select
		$scope.itemSelect = function(item) {
			item.intoMachine = !item.intoMachine;
			if ($scope.selectedMix !== null) {
				$scope.checkMix();
			}
		};

		// check mix status
		$scope.checkMix = function() {
			var itemsIntoMachine = [];
			angular.forEach($scope.characterItems, function(item) {
				if (item.intoMachine === true) {
					itemsIntoMachine.push({
						"slot": item.slot.i
					});
				}
			});

			var indexSelectedMix = $scope.mixList.indexOf($scope.selectedMix);

			MachineService.checkMix(false, $scope.selectedCharacter.name, indexSelectedMix, itemsIntoMachine)
				.then(function(data) {
					$scope.selectedMix.requirements = data;

					var enabledStatus = true;
					angular.forEach($scope.selectedMix.requirements, function(requirement) {
						if (requirement.amount !== requirement.haveAmount) {
							enabledStatus = false;
						}
					});
					$scope.mixButtonState = enabledStatus;
				});
		};

		// execute mix
		$scope.executeMix = function() {
			$scope.mixButtonState = false; // desabilita o botao de mix

			var itemsIntoMachine = [];
			angular.forEach($scope.characterItems, function(item) {
				if (item.intoMachine === true) {
					itemsIntoMachine.push({
						"slot": item.slot.i
					});
				}
			});

			var indexSelectedMix = $scope.mixList.indexOf($scope.selectedMix);

			MachineService.checkMix(true, $scope.selectedCharacter.name, indexSelectedMix, itemsIntoMachine)
				.then(function(data) {
					$scope.machineAlert = data;
				});
		};

		$scope.machineAlertClose = function() {
			if($scope.machineAlert.code === "NEED_DISCONNECT_ACCOUNT"){
				$state.go("machine.authentication.signout");
			} else if($scope.machineAlert.code === "MACHINE_MIX_STATUS_NOT_HAVE_SPACE"){
				$scope.mixButtonState = true; // habilita o botao de mix
			} else { //sucesso ou falha
				$scope.getCharacterItems();
				$scope.checkMix();
			}
			$scope.machineAlert = null;
		};

		$scope.init = function() {

			MachineService.getMixes()
				.then(function(data) {
					$scope.mixList = data;
				});

			CharacterService.getCharacterList()
				.then(function(data) {
					$scope.characterList = data;
				});
		};
		$scope.init();
	}
]);