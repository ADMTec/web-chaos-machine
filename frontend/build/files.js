/* jshint strict: false */
module.exports = {

  external_scripts: [
    "app/bower_components/angular/angular.js",
    "app/bower_components/angular-locker/dist/angular-locker.js",
    "app/bower_components/angular-translate/angular-translate.js",
    "app/bower_components/angular-translate-loader-static-files/angular-translate-loader-static-files.js",
    "app/bower_components/ui-router/release/angular-ui-router.js"
  ],

  app_scripts: [
    // initializers
    "app/javascripts/initializers/modules.js", // must go first
    "app/javascripts/initializers/**/*.js",

    // filters
    "app/javascripts/filters/**/*.js",

    // directives
    "app/javascripts/directives/**/*.js",

    // helpers
    "app/javascripts/helpers/**/*.js",

    // services
    "app/javascripts/services/**/*.js",

    // controllers
    "app/javascripts/controllers/**/*.js"
  ],

  locales: [
    "app/locales/*.json",
  ],

  styles: [
    "app/stylesheets/**/*.css",
  ],

  views: [
    "app/views/**",
  ],

  images: [
    "app/images/**",
  ],

  app: [
    "app/*.html"
  ]

};
