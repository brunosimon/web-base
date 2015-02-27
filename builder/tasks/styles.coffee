
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
plumber			= require 'gulp-plumber'
sass	 		= require 'gulp-sass'
autoprefixer 	= require 'gulp-autoprefixer'
minifyCss		= require 'gulp-minify-css'


# ---------------------------------------------------------------------o variables

config = require('../config.json')


# ---------------------------------------------------------------------o task

	# ---------------------------------------------------o dev task

gulp.task 'styles:dev', ->

	for i in [0...config.styles.length]

		src = []
		for j in [0...config.styles[i].src.length]
			src.push(config.src + config.styles[i].src[j])

		dest = config.styles[i].dest

		gulp
			.src( src )
	    	.pipe(plumber())
			.pipe(sass({ 'soucemap=none' })) #'sourcemap=none': true
			.pipe(autoprefixer({
				 browsers: ['last 2 versions']
			}))
			.pipe(gulp.dest( dest ))


	# ---------------------------------------------------o prod task -> minify

gulp.task 'styles:prod', ->

	for i in [0...config.styles.length]

		src = []
		for j in [0...config.styles[i].src.length]
			src.push(config.src + config.styles[i].src[j])

		dest = config.styles[i].dest

		gulp
			.src( src )
	    	.pipe(plumber())
			.pipe(sass({ 'soucemap=none' })) #'sourcemap=none': true
			.pipe(autoprefixer({
				 browsers: ['last 2 versions']
			}))
			.pipe(minifyCss())
			.pipe(gulp.dest( dest ))