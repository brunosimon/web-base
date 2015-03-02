
# ---------------------------------------------------------------------o modules

gulp 			= require 'gulp'
spritesmith		= require 'gulp.spritesmith'
svgmin			= require 'gulp-svgmin'
svgstore		= require 'gulp-svgstore'


# ---------------------------------------------------------------------o variables

config = require('../config.json')


# ---------------------------------------------------------------------o task

gulp.task 'sprites', ->

	for j in [0...config.sprite.length]

		sprite = config.sprite[j]

		src = []
		for j in [0...sprite.src.length]
			src.push(config.src + sprite.src[j])

		if sprite.type == 'png'
			spriteData = gulp.src( src )
				.pipe(spritesmith({
					imgName: sprite.filename
					cssName: sprite.stylename
					algorithm: 'binary-tree'
					cssTemplate: config.src + 'sprite/templates/sprite.mustache'
					cssFormat: 'scss'
					imgPath: '/' + sprite.dest + '/' + sprite.filename
					padding: 1
				}))

			imgDest = sprite.dest
			cssDest = config.src + sprite.cssDest
			
			spriteData.img.pipe(gulp.dest(imgDest))
			spriteData.css.pipe(gulp.dest(cssDest))

		else

			gulp
				.src(config.src + sprite.src)
				.pipe(svgmin())
				.pipe(svgstore({
					fileName: sprite.filename
					inlineSvg: true
					emptyFills: true
				}))
				.pipe(gulp.dest(sprite.dest))