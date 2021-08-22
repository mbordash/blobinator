// Example of how to zip a directory
var gulp = require('gulp');

gulp.task('copy', function () {
    return gulp.src([
        './**/*',
        '!./{node_modules,node_modules/**/*}',
        '!./assets/{sass,sass/*}',
        '!./gulpfile.js',
        '!./package.json',
        '!./package-lock.json',
        '!./admin/js/blobinator-admin-panel/{node_modules,node_modules/**/*}',
        '!./admin/js/blobinator-admin-panel/package.json',
        '!./admin/js/blobinator-admin-panel/package-lock.json',
        '!./admin/js/blobinator-admin-panel/{src,src/*}',
    ])
        .pipe(gulp.dest('../blobinator-svn/trunk/'));
});