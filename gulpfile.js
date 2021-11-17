var gulp = require('gulp');
var imagemin = require('gulp-imagemin');
var cleancss = require('gulp-clean-css');

var paths ={
  srcDir: 'img',
  dstDir: 'dist'
}


gulp.task('minify-css', function(){
  return gulp.src("css/*.css")
  .pipe(cleancss())
  .pipe(gulp.dest('dist/css/'));
});

gulp.task('default',['minify-css']);

gulp.task('imagemin', function(){
  var srcGlob = paths.srcDir+'/*.+(jpg|jpeg|png|gif|svg)';
  var dstGlob = paths.dstDir+'/img';
  gulp.src( srcGlob )
    .pipe(changed( dstGlob ))
    .pipe(imagemin([
      imagemin.gifsicle({interlaced: true}),
      imagemin.jpegtran({progressive: true}),
      imagemin.optipng({optimizationLevel: 5})
      ]
    ))
  .pipe(gulp.dest(dstGlob));
});

gulp.task('watch', function() {
  gulp.watch(paths.srcDir+'/*', ['imagemin']);
});

