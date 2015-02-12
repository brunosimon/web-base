
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
plumber			= require 'gulp-plumber'
rename	 		= require 'gulp-rename'
imagemin 		= require 'gulp-imagemin'
pngquant 		= require 'imagemin-pngquant'
jpegtran 		= require 'imagemin-jpegtran'
webp	 		= require 'imagemin-webp'


# ---------------------------------------------------------------------o variables

config = require('../config.json')


# ---------------------------------------------------------------------o task

gulp.task 'imageoptim', ->

	for i in [0...config.image.length]

		src = config.src + config.image[i].src
		dest = config.image[i].dest

		gulp
			.src( src )
			.pipe(imagemin({
				progressive: true
				use: [pngquant(), jpegtran()]
			}))
	    	.pipe(plumber())
			.pipe(gulp.dest( dest ))
	    	.pipe(rename( (path) ->
	    		path.basename += path.extname
	    		return
	    	))
			.pipe(webp({
				#quality: 100
				#lossless: false
				#alphaQuality: 100
				#sharpness: 7
				#method: 6
			})())
	    	.pipe(plumber())
			.pipe(gulp.dest( dest ))