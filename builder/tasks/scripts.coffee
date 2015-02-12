
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
plumber			= require 'gulp-plumber'
browserify 		= require 'gulp-browserify'
rename 			= require 'gulp-rename'


# ---------------------------------------------------------------------o variables

config = require('../config.json')


# ---------------------------------------------------------------------o task

	# ---------------------------------------------------o dev task

gulp.task 'scripts', ->
	
	for i in [0...config.scripts.length]

		src = config.src + config.scripts[i].srcPath + '/' + config.scripts[i].srcFilename
		dest = config.scripts[i].dest
		filename = config.scripts[i].filename

		gulp
			.src( src, { read: false } )
    		.pipe(plumber())
			.pipe(browserify({
				transform: ['coffeeify']
				paths: ['./node_modules', config.src + config.scripts[i].srcPath, config.src + 'coffee/shared']
				extensions: ['.coffee']
			}))
			.pipe(rename( filename ))
			.pipe(gulp.dest( dest ))


	# ---------------------------------------------------o dev task -> minify

###
gulp.src('../src/coffee/test/test.coffee', { read: false })
		.pipe(browserify({
			transform: ['coffeeify']
			paths: ['./node_modules','../src/coffee/test']
			extensions: ['.coffee']
		}))
		.pipe(rename('test.js'))
		.pipe(gulp.dest('../site/dev/assets/js/'))
###