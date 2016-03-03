/* jshint strict: false */
module.exports = {

  root_dest: "./app_dist",

  scripts: {
    dest: "./app_dist/javascripts",
    filename: "application.js",
    watchlist: "./app/javascripts/**/*.js",
  },

  locales: {
    dest: "./app_dist/locales",
    watchlist: "app/locales/*.json",
  },

  styles: {
    dest: "./app_dist/stylesheets",
    filename: "application.css",
    watchlist: "app/stylesheets/**/*.css",
  },

  images: {
    dest: "./app_dist/images",
    watchlist: "app/images/*",
  },

  app: {
    dest: "./app_dist",
    watchlist: "app/*.html",
  },

  views: {
    dest: "./app_dist/views",
    watchlist: "app/views/**/*.html",
  },

};
