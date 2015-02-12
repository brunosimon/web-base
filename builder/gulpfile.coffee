
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
requireDir 		= require 'require-dir'
path			= require 'path'


# ---------------------------------------------------------------------o require tasks

requireDir('./tasks', { recurse: true })


# ---------------------------------------------------------------------o variables

config = require('./config.json')


# ---------------------------------------------------------------------o default task

gulp.task 'default', () =>
	
	gulp.start('sprites') 					# will launch sass via watch
	gulp.start('scripts')

	gulp.watch([config.src + '/**/*'], (event) =>

		ext = path.extname(event.path)
		pathArray = event.path.split('/')
		folder = pathArray[pathArray.length - 2]

		switch ext
			when '.png', '.svg', '.mustache'
				if 'favicons' in pathArray
					taskname = 'favicons'
				else
					taskname = 'sprites'
			when '.coffee'
				taskname = 'scripts'
			when '.scss'
				taskname = 'styles:dev'

		if taskname
			gulp.start( taskname )

	)