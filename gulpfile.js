const gulp = require("gulp");
const sass = require('gulp-sass')(require('sass'));

var uglify = require('gulp-uglify');

gulp.task("sass", async function(){
    gulp.src("development/*.scss")
        .pipe(sass().on("error", sass.logError))
        .pipe(gulp.dest("public/css"))
});

gulp.task('cfajs', async function () {
    gulp.src('development/*.js')
        .pipe(uglify())
        .pipe(gulp.dest("public/js"))
});