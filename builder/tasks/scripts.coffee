gulp.task 'coffee', ->

	for i in [0...config.coffee.length]

		src = []
		for j in [0...config.coffee[i].src.length]
			src.push(config.src + config.coffee[i].src[j])

		dest = config.src + config.coffee[i].dest
		filename = config.coffee[i].filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(coffee({ bare: true }))
			.pipe(gulp.dest( dest ))

gulp.task 'js:dev', ->

	for i in [0...config.js.length]

		src = []
		for j in [0...config.js[i].src.length]
			src.push(config.src + config.js[i].src[j])

		dest = config.js[i].dest
		filename = config.js[i].filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(gulp.dest( dest ))


gulp.task 'js:dist', ->

	for i in [0...config.js.length]

		src = []
		for j in [0...config.js[i].src.length]
			src.push(config.src + config.js[i].src[j])

		dest = config.js[i].dest
		filename = config.js[i].filename

		gulp
			.src( src )
    		.pipe(plumber())
			.pipe(concat( filename ))
			.pipe(uglify())
			.pipe(gulp.dest( dest ))