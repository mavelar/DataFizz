'use strict';
 
// REQUIRES
var gulp = require('gulp');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');

// CONFIGS
var sassSrc = './build/sass/*.scss';
var sassDest = './public/assets/css';

var sassConfig = {
	outputStyle: 'compressed'
};

var jsSrc = './build/javascript/**/*.js';
var jsDest = './public/assets/js';

var uglifyConfig = {
	preserveComments: 'some'
};

// TASKS
gulp.task('sass', function () {
  gulp.src(sassSrc)
  	.pipe(sass(sassConfig))
  	.pipe(rename({ extname: '.min.css' }))
    .pipe(gulp.dest(sassDest));
});

gulp.task('uglify', function () {
  gulp.src(jsSrc)
  	.pipe(uglify(uglifyConfig))
  	.pipe(rename({ extname: '.min.js' }))
    .pipe(gulp.dest(jsDest));
});

gulp.task('watch', function () {
  gulp.watch('./build/sass/**/*.scss', ['sass']);
  gulp.watch(jsSrc, ['uglify']);
});