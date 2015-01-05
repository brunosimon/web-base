gulp.task 'sprites', ->

	for j in [0...config.sprite.length]

		sprite = config.sprite[j]

		if sprite.type == 'png'
			spriteData = gulp.src(config.src + sprite.src).pipe(spritesmith({
				imgName: sprite.filename
				cssName: sprite.stylename
				algorithm: 'binary-tree'
				cssTemplate: config.src + sprite.template
				cssFormat: 'scss'
				imgPath: '../img/sprite/' + sprite.filename
				padding: 1
			}))

			imgDest = config.dest + sprite.dest
			cssDest = config.src + 'scss/shared/'
			
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