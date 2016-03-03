angular.module("webcm.services").factory("ApiService", ["$http", "$q",
	function($http, $q) {
		"use strict";
		return {
			/**
			 * Realiza as requisições
			 * @param  {string} url    URL para a requisição
			 * @param  {json} data   Dados para enviar para o servidor
			 * @param  {string} method Método a ser executado
			 * @return {json}        Resposta do servidor
			 */
			request: function(url, data, method) {
				var deferrer = new $q.defer();
				$http({
					url: "./api/index.php?" + url,
					method: method,
					data: data,
					headers: {
						"Content-Type": "application/json"
					}
				}).success(function(data) {
					deferrer.resolve(data);
				}).error(function(data) {
					deferrer.reject(data);
				});
				return deferrer.promise;
			},

			/**
			 * Realiza uma requisição GET
			 * @param  {string} url    URL para a requisição
			 * @param  {json} data   Dados para enviar para o servidor
			 * @return {json}        Resposta do servidor
			 */
			get: function(url, data) {
				return this.request(url, data, "GET");
			},

			/**
			 * Realiza uma requisição POST
			 * @param  {string} url    URL para a requisição
			 * @param  {json} data   Dados para enviar para o servidor
			 * @return {json}        Resposta do servidor
			 */
			post: function(url, data) {
				return this.request(url, data, "POST");
			},

			/**
			 * Realiza uma requisição PUT
			 * @param  {string} url    URL para a requisição
			 * @param  {json} data   Dados para enviar para o servidor
			 * @return {json}        Resposta do servidor
			 */
			put: function(url, data) {
				return this.request(url, data, "PUT");
			},

			/**
			 * Realiza uma requisição DELETE
			 * @param  {string} url    URL para a requisição
			 * @param  {json} data   Dados para enviar para o servidor
			 * @return {json}        Resposta do servidor
			 */
			delete: function(url, data) {
				return this.request(url, data, "DELETE");
			}
		};
	}
]);