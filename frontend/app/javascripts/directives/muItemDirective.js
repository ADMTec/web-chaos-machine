angular.module("webcm.directives").directive("muItem", ["$compile", "$rootScope",
	function($compile, $rootScope) {
		"use strict";
		return {
			restrict: "E",
			scope: {
				item: "=item"
			},
			link: function(scope, element, attrs) {
				element.addClass("cursor");

				var tempItemImage = 
					(scope.item.itemIdSection <= 9 ? "0" + scope.item.itemIdSection : scope.item.itemIdSection) + "" + 
					(scope.item.itemIdIndex <= 9 ? "00" + scope.item.itemIdIndex : (scope.item.itemIdIndex <= 99 ? "0" + scope.item.itemIdIndex : scope.item.itemIdIndex));
				// olha se precisa setar o level no nome da imagem
				if(
					(scope.item.itemIdSection === 12 && scope.item.itemIdIndex === 11) || 
					(scope.item.itemIdSection === 12 && scope.item.itemIdIndex === 26) || 
					(scope.item.itemIdSection === 12 && scope.item.itemIdIndex === 30) || 
					(scope.item.itemIdSection === 12 && scope.item.itemIdIndex === 31) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 7) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 10) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 11) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 13) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 14) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 15) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 16) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 17) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 18) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 19) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 20) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 31) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 49) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 50) || 
					(scope.item.itemIdSection === 13 && scope.item.itemIdIndex === 51) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 7) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 9) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 11) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 12) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 17) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 18) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 19) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 21) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 22) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 23) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 24) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 25) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 26) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 27) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 28) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 29) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 31) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 32) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 33) || 
					(scope.item.itemIdSection === 14 && scope.item.itemIdIndex === 34) || 
					(scope.item.itemIdSection === 15 && scope.item.itemIdIndex === 29)
				) {
					tempItemImage += (scope.item.itemLevel <= 9 ? "0" + scope.item.itemLevel : scope.item.itemLevel);
				}
				else {
					tempItemImage += "00";
				}
				if (scope.item.slot.i >= 12) {
					var theme = $rootScope.theme;
					element.css("background", "url(./images/items/" + tempItemImage + ".gif) no-repeat center center, url(./images/slot-" + theme + ".png) repeat");
					element.css("width", (32 * scope.item.item.x) + "px");
					element.css("height", (32 * scope.item.item.y) + "px");
					element.css("margin", (32 * scope.item.slot.y) + "px 0 0 " + (32 * scope.item.slot.x) + "px");
				} else {
					element.css("background", "url(./images/items/" + tempItemImage + ".gif) no-repeat center center");
				}

				var description = "<div class=\"tooltip\">";
				description += "<p class=\"" + (scope.item.itemExcellents[6] > 0 ? "green" : "yellow") + "\">" + scope.item.itemName + " +" + scope.item.itemLevel + "</p>";
				description += "<p>&nbsp;</p>";
				description += "<p class=\"white\" translate=\"ITEM_OPTION_DURABILITY\" translate-value-durability=\"" + scope.item.itemDurability + "\"></p>";
				description += "<p>&nbsp;</p>";

				var excellentType = null;
				if (scope.item.itemIdSection >= 0 && scope.item.itemIdSection <= 5) {
					excellentType = "ATTACK";
				} else if (scope.item.itemIdSection >= 6 && scope.item.itemIdSection <= 11) {
					excellentType = "DEFENSE";
				} else if (scope.item.itemIdSection === 12) {
					excellentType = "WINGLV1-2";
				} else if (scope.item.itemIdSection === 13) {
					excellentType = "DEFENSE";
				} else {
					excellentType = "DEFENSE";
				}

				if (scope.item.itemExcellents[0]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_0\"></p>";
				}
				if (scope.item.itemExcellents[1]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_1\"></p>";
				}
				if (scope.item.itemExcellents[2]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_2\"></p>";
				}
				if (scope.item.itemExcellents[3]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_3\"></p>";
				}
				if (scope.item.itemExcellents[4]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_4\"></p>";
				}
				if (scope.item.itemExcellents[5]) {
					description += "<p class=\"blue\" translate=\"ITEM_OPTION_EXCELLENT_" + excellentType + "_5\"></p>";
				}
				description += "</div>";
				element.append(description);

				var elementTooltip = element.find("div").eq(0);
				element.on("mouseover", function(e) {
					elementTooltip.css("display", "block");
				}).on("mouseout", function(e) {
					elementTooltip.css("display", "none");
				}).on("mousemove", function(e) {
					//Chrome use offset, firefox use layer
					var x = e.offsetX === undefined ? e.layerX : e.offsetX;
					var y = e.offsetY === undefined ? e.layerY : e.offsetY;
					elementTooltip.css("top", (y + 10) + "px");
					elementTooltip.css("left", (x + 10) + "px");
				});

				$compile(element.contents())(scope);
			},
			template: ""
		};
	}
]);