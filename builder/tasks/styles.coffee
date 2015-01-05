gulp.task 'sass:dev', ->

	for i in [0...config.sass.length]

		src = []
		for j in [0...config.sass[i].src.length]
			src.push(config.src + config.sass[i].src[j])

		dest = config.sass[i].dest

		gulp
			.src( src )
	    	.pipe(plumber())
			.pipe(sass({  })) #'sourcemap=none': true
			.pipe(gulp.dest( dest ))


gulp.task 'sass:dist', ->

	for i in [0...config.sass.length]

		src = []
		for j in [0...config.sass[i].src.length]
			src.push(config.src + config.sass[i].src[j])

		dest = config.sass[i].dest

		gulp
			.src( src )
	    	.pipe(plumber())
			.pipe(sass({ style: 'expanded' }))
			.pipe(minifyCss())
			.pipe(gulp.dest( dest ))