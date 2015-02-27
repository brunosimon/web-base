
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
requireDir 		= require 'require-dir'
path			= require 'path'


# ---------------------------------------------------------------------o require tasks

requireDir('./tasks', { recurse: true })


# ---------------------------------------------------------------------o variables

config = require('./config.json')

###
console.log config.styles


fabricator = {
	styles: {
		src: 
		dest:

	}
}
config.styles.push {}
###


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
				taskname = 'coffee'
			when '.js'
				taskname = 'scripts'
			when '.scss'
				taskname = 'styles:dev'

		if taskname
			gulp.start( taskname )

	)

# ---------------------------------------------------------------------o minify task

gulp.task 'dist', () =>

	gulp.start('scripts:dist')
	gulp.start('styles:dist')