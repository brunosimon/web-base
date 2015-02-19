
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
plumber			= require 'gulp-plumber'
#browserify 		= require 'gulp-browserify'
coffee			= require 'gulp-coffee'
concat 			= require 'gulp-concat'
uglify 			= require 'gulp-uglify'


# ---------------------------------------------------------------------o variables

config = require('../config.json')


# ---------------------------------------------------------------------o task

	# ---------------------------------------------------o dev task

###
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
###

gulp.task 'coffee', ->

	for i in [0...config.scripts.length]

		script = config.scripts[i].coffee

		src = []
		for j in [0...script.src.length]
			src.push(config.src + script.src[j])

		dest = config.src + script.dest
		filename = script.filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(coffee({ bare: true }))
			.pipe(gulp.dest( dest ))


gulp.task 'scripts', ->

	for i in [0...config.scripts.length]

		script = config.scripts[i].javascript

		src = []
		for j in [0...script.src.length]
			src.push(config.src + script.src[j])

		dest = script.dest
		filename = script.filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(gulp.dest( dest ))


	# ---------------------------------------------------o dev task -> minify

gulp.task 'scripts:dist', ->

	for i in [0...config.scripts.length]

		script = config.javascript[i].coffee

		src = []
		for j in [0...script.src.length]
			src.push(config.src + script.src[j])

		dest = script.dest
		filename = script.filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(uglify())
			.pipe(gulp.dest( dest ))