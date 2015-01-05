gulp.task 'favicons', ->

	src = config.favicons.src
	dest = config.favicons.dest

	for i in [0...config.favicons.type.length]
		name = config.favicons.type[i].name
		ext = config.favicons.type[i].ext

		for j in [0...config.favicons.type[i].sizes.length]
			size = config.favicons.type[i].sizes[j]
			filename = config.favicons.dest + name + '-' + size + 'x' + size


			gulp
				.src(config.src + config.favicons.src + name + ext)
				.pipe(imageResize({
					width: size
					height: size
				}))
				.pipe(rename({
					basename: filename
				}))
				.pipe(gulp.dest(config.dest + config.favicons.dest))

			if name == 'favicon' && j == 0

				gulp
					.src(config.dest + filename + '.png')
					.pipe(rename({
						basename: 'favicon'
						extname: '.ico'
					}))
					.pipe(gulp.dest(config.dest))