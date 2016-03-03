describe("ApiService", function() {
	"use strict";

	beforeEach(module("app.services"));

	beforeEach(inject(function($httpBackend, ApiService) {
		this.httpBackend = $httpBackend;
		this.service = ApiService;
	}));

	describe("make a error", function() {
		it("try make a error", function() {

			this.httpBackend.expectGET("http://test.com/api/not-found").respond(404, "error");

			this.service.get("http://test.com/api/not-found");

			this.httpBackend.flush();
		});
	});

	describe("get()", function() {
		it("make a http get request", function() {
			this.httpBackend.expectGET("http://test.com/api/test").respond(201, "http response");

			this.service.get("http://test.com/api/test");

			this.httpBackend.flush();
		});
	});

	describe("post()", function() {
		it("make a http post request", function() {
			this.httpBackend.expectPOST("http://test.com/api/test", { test: "anyData" }).respond(201, "http response");

			this.service.post("http://test.com/api/test", { test: "anyData" });

			this.httpBackend.flush();
		});
	});

	describe("put()", function() {
		it("make a http put request", function() {
			this.httpBackend.expectPUT("http://test.com/api/test", { test: "anyData" }).respond(201, "http response");

			this.service.put("http://test.com/api/test", { test: "anyData" });

			this.httpBackend.flush();
		});
	});

	describe("delete()", function() {
		it("make a http delete request", function() {
			this.httpBackend.expectDELETE("http://test.com/api/test").respond(201, "http response");

			this.service.delete("http://test.com/api/test");

			this.httpBackend.flush();
		});
	});
});