# ---------------------------------------------------------------------o plugins


gulp 			= require 'gulp'

plumber			= require 'gulp-plumber'
concat			= require 'gulp-concat'
coffee			= require 'gulp-coffee'
uglify			= require 'gulp-uglify'
sass			= require 'gulp-ruby-sass'
minifyCss		= require 'gulp-minify-css'
svgmin			= require 'gulp-svgmin'
svgstore		= require 'gulp-svgstore'
path			= require 'path'
gulpif 			= require 'gulp-if'
spritesmith		= require 'gulp.spritesmith'
imageResize 	= require 'gulp-image-resize'
rename	 		= require 'gulp-rename'
fileinclude		= require 'gulp-file-include'

fs 				= require 'fs'


gulp.task 'default', () =>
	fs.readdir('tasks', (err, files) =>
		for file in files
			require file
	)

###
	
	TODO:
		- use js map: example — var foo = function (i) { console.log(i) }; [0, 1, 2, 3].map(foo);

###

# ---------------------------------------------------------------------o variables

config = require('./config.json')

tasks = [
	{
		name: 'js:dev'
		extensions: '.js'
		preTasks: ['coffee']
	}
	{
		name: 'js:dev'
		extensions: '.js'
	}
] 


# ---------------------------------------------------------------------o load tasks

gulp.task 'default', () =>
	

	###
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
	###