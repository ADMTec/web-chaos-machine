angular.module("webcm.directives").directive("muSelect", function() {
	"use strict";
	return {
		restrict: "E",
		scope: {
			optionsList: "=list",
			callback: "=callback"
		},
		link: function(scope, element, attrs) {
			scope.open = false;
			scope.showSelectLabelOption = true;

			scope.selectOption = function(option) {
				if (scope.optionsList.length > 0) {
					scope.showSelectLabelOption = true;
					if (scope.open === false) {
						scope.open = true;
					} else if (option !== null) {
						angular.forEach(scope.optionsList, function(e) {
							e.selected = false;
						});
						option.selected = true;
						scope.open = false;
						scope.showSelectLabelOption = false;
						scope.callback(option);
					}
				}
			};
		},
		template: "<ul class=\"select\">" +
			"<li ng-class=\"{ 'selected' : showSelectLabelOption }\" ng-click=\"selectOption(null);\" translate=\"MU_SELECT_DEFAULT_OPTION\"></li>" +
			"<li ng-repeat=\"option in optionsList\" ng-class=\"{ 'selected' : option.selected || open }\" ng-click=\"selectOption(option);\">{{option.name}}</li>" +
			"</ul>"
	};
});