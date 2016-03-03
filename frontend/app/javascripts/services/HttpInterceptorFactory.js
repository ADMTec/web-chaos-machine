angular.module("webcm").factory("HttpInterceptorFactory", ["$q",
	function($q){
		"use strict";
		
		var interceptor = {
	        responseError: function(rejection) {
				alert(rejection.data);
	            return $q.reject(rejection);
	        }
        };

		return interceptor;
	}
]);