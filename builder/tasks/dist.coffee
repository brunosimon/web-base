gulp.task 'dist', () =>

	gulp.start('js:dist')
	gulp.start('sass:dist')