/* jshint strict: false */
var gulp = require("gulp");
var concat = require("gulp-concat");
var del = require("del");
var uglify = require("gulp-uglify");
var minifyCSS = require("gulp-minify-css");
//var obfuscate = require("gulp-obfuscate");

var config = require("./build/config.js");
var files = require("./build/files.js");

// Clear task
gulp.task("clear", function() {
  del(config.root_dest);
});

// Scripts task
gulp.task("scripts", function() {
  del(config.scripts.dest, function() {
    var scripts = files.external_scripts.concat(files.app_scripts);

    gulp.src(scripts)
      .pipe(concat(config.scripts.filename))
      .pipe(uglify())
      //.pipe(obfuscate())
      .pipe(gulp.dest(config.scripts.dest));
  });
});

// Locales task
gulp.task("locales", function() {
  del(config.locales.dest, function() {
    gulp.src(files.locales)
      .pipe(gulp.dest(config.locales.dest));
  });
});

// Styles task
gulp.task("styles", function() {
  del(config.styles.dest, function() {
    gulp.src(files.styles)
      .pipe(concat(config.styles.filename))
      //.pipe(minifyCSS())
      .pipe(gulp.dest(config.styles.dest));
  });
});

// Templates task
gulp.task("views", function() {
  del(config.views.dest, function() {
    gulp.src(files.views)
      .pipe(gulp.dest(config.views.dest));
  });
});

// Images task
gulp.task("images", function() {
  del(config.images.dest, function() {
    gulp.src(files.images)
      .pipe(gulp.dest(config.images.dest));
  });
});

// App task
gulp.task("app", function() {
  del(config.app.dest, function() {
    gulp.src(files.app)
      .pipe(gulp.dest(config.app.dest));
  });
});

// Watch task
gulp.task("watch", ["default"], function() {
  gulp.watch(config.scripts.watchlist, ["scripts"]);
  gulp.watch(config.locales.watchlist, ["locales"]);
  gulp.watch(config.styles.watchlist, ["styles"]);
  gulp.watch(config.views.watchlist, ["views"]);
  gulp.watch(config.images.watchlist, ["images"]);
  gulp.watch(config.app.watchlist, ["app"]);
});

// Default task & watch
gulp.task("default", [
  "scripts",
  "locales",
  "styles",
  "views",
  "images",
  "app"
]);