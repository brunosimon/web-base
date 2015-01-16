# ---------------------------------------------------------------------o plugins

gulp 			= require 'gulp'
plumber			= require 'gulp-plumber'
concat			= require 'gulp-concat'
coffee			= require 'gulp-coffee'
uglify			= require 'gulp-uglify'
sass			= require 'gulp-sass'
minifyCss		= require 'gulp-minify-css'
svgmin			= require 'gulp-svgmin'
svgstore		= require 'gulp-svgstore'
path			= require 'path'
spritesmith		= require 'gulp.spritesmith'
imageResize 	= require 'gulp-image-resize'
rename	 		= require 'gulp-rename'
autoprefixer 	= require 'gulp-autoprefixer'
imagemin 		= require 'gulp-imagemin'
pngquant 		= require 'imagemin-pngquant'
jpegtran 		= require 'imagemin-jpegtran'
webp	 		= require 'imagemin-webp'
fs 				= require 'fs'

require('./gulpfile.js')


# ---------------------------------------------------------------------o variables

config = require('./config.json')


# ---------------------------------------------------------------------o scripts

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


# ---------------------------------------------------------------------o styles

gulp.task 'sass:dev', ->

	for i in [0...config.sass.length]

		src = []
		for j in [0...config.sass[i].src.length]
			src.push(config.src + config.sass[i].src[j])

		dest = config.sass[i].dest

		gulp
			.src( src )
	    	.pipe(plumber())
			.pipe(sass({ 'soucemap=none' })) #'sourcemap=none': true
			.pipe(autoprefixer({
				 browsers: ['last 2 versions']
			}))
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



# ---------------------------------------------------------------------o sprites

gulp.task 'sprites', ->

	for j in [0...config.sprite.length]

		sprite = config.sprite[j]

		if sprite.type == 'png'
			spriteData = gulp.src(config.src + sprite.src).pipe(spritesmith({
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



# ---------------------------------------------------------------------o favicons

gulp.task 'favicons', ->

	src = config.favicons.src
	dest = config.favicons.dest

	for i in [0...config.favicons.type.length]
		name = config.favicons.type[i].name
		ext = config.favicons.type[i].ext

		for j in [0...config.favicons.type[i].sizes.length]
			size = config.favicons.type[i].sizes[j]
			filename = name + '-' + size + 'x' + size


			gulp
				.src(config.src + src + name + ext)
				.pipe(imageResize({
					width: size
					height: size
				}))
				.pipe(rename({
					basename: filename
				}))
				.pipe(gulp.dest(dest))

			if name == 'favicon' && j == 0

				gulp
					.src(dest + filename + '.png')
					.pipe(rename({
						basename: 'favicon'
						extname: '.ico'
					}))
					.pipe(gulp.dest(config.dest))
	

# ---------------------------------------------------------------------o optimize pictures

gulp.task 'imageoptim', ['imageoptim_compress, imageoptim_converwebp'], ->

	return

gulp.task 'imageoptim_convertwebp', ->

	for i in [0...config.image.length]

		dest = config.image[i].dest

		gulp
			.src( dest + '/**/*.{jpg,png}' )
			.pipe(webp({
				#quality: 100
				#lossless: false
				#alphaQuality: 100
				#sharpness: 7
				#method: 6
			})())
	    	.pipe(plumber())
			.pipe(gulp.dest( dest ))


gulp.task 'imageoptim_compress', ->

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





# ---------------------------------------------------------------------o default task

gulp.task 'default', () =>
	
	gulp.start('sprites') 					# will launch sass via watch
	gulp.start('coffee')					# will launch js via watch

	gulp.watch([config.src + '/**/*'], (event) =>

		ext = path.extname(event.path)
		pathArray = event.path.split('/')
		folder = pathArray[pathArray.length - 2]

		switch ext
			when '.png', '.svg', '.mustache'
				if 'favicons' in pathArray
					taskname = 'favicons'
				else
					taskname = 'sprites'
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
	

gulp.task 'dist', () =>

	gulp.start('js:dist')
	gulp.start('sass:dist')





