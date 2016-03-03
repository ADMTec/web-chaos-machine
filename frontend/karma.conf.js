module.exports = function(config){
  "use strict";
  
  config.set({

    basePath : "./",

    files : [
      "app/bower_components/angular/angular.js",
      "app/bower_components/ui-router/release/angular-ui-router.js",
      "app/bower_components/angular-locker/dist/angular-locker.js",
      "app/bower_components/angular-mocks/angular-mocks.js",
      "app/javascripts/initializers/modules.js",
      "app/javascripts/initializers/routes.js",
      "app/javascripts/controllers/**/*.js",
      "app/javascripts/services/**/*.js",
      "app/javascripts/filters/**/*.js",
      "app/javascripts/helpers/**/*.js",
      "test/**/*.js"
    ],

    autoWatch : true,

    frameworks: ["jasmine"],

    browsers : ["Chrome"],

    plugins : [
            "karma-chrome-launcher",
            "karma-firefox-launcher",
            "karma-jasmine",
            "karma-coverage"
            ],

    // coverage reporter generates the coverage
    reporters: ["progress", "coverage"],

    preprocessors: {
      // source files, that you wanna generate coverage for
      // do not include tests or libraries
      // (these files will be instrumented by Istanbul)
      "app/javascripts/**/*.js": ["coverage"]
    },

    // optionally, configure the reporter
    coverageReporter: {
      type : "html",
      dir : "coverage/"
    }

  });
};
