gulp.task 'default', () =>
	
	gulp.start('js:dev', ['coffee'])
	gulp.start('sprites')
	gulp.start('sass:dev')
	# gulp.start('favicons')

	gulp.watch([config.src + '/**/*'], (event) =>

		ext = path.extname(event.path)
		pathArray = event.path.split('/')
		folder = pathArray[pathArray.length - 2]

		switch ext
			when '.png', '.svg', '.mustache'
				if 'favicons' in pathArray
					taskname = 'favicons'
				# else
					# taskname = 'sprites'
			when '.coffee'
				taskname = 'coffee'
			when '.js'
				taskname = 'js:dev'
			when '.scss'
				taskname = 'sass:dev'
			when '.html'
				taskname = 'views'

		if taskname
			gulp.start( taskname )

	)