
# ---------------------------------------------------------------------o modules

browserify 			= require('browserify')
browserSync 		= require('browser-sync')
styleguide:collate 	= require('./collate')
styleguide:compile 	= require('./compile')
concat 				= require('gulp-concat')
csso 				= require('gulp-csso')
del 				= require('del')
gulp 				= require('gulp')
gutil 				= require('gulp-util')
gulpif 				= require('gulp-if')
imagemin 			= require('gulp-imagemin')
prefix 				= require('gulp-autoprefixer')
Q 					= require('q')
rename 				= require('gulp-rename')
reload 				= browserSync.reload
runSequence 		= require('run-sequence')
sass 				= require('gulp-sass')
source 				= require('vinyl-source-stream')
streamify 			= require('gulp-streamify')
uglify 				= require('gulp-uglify')


# ---------------------------------------------------------------------o variables

config = require('../../config.json')
config = config.styleguide
isDev = gutil.env.dev


# -----------------------------------------------------------------------o Clean

gulp.task 'styleguide:clean', (cb) ->
	del([config.dest], {force: true}, cb)


# -----------------------------------------------------------------------o Styles

gulp.task 'styleguide:styles:fabricator', () ->
	gulp.src(config.src.styles.fabricator)
		.pipe(sass({
			errLogToConsole: true
		}))
		.pipe(prefix('last 1 version'))
		.pipe(gulpif(!isDev, csso()))
		.pipe(rename('f.css'))
		.pipe(gulp.dest(config.dest + '/fabricator/styles'))
		.pipe(gulpif(isDev, reload({stream:true})))


gulp.task 'styleguide:styles:toolkit', () ->
	gulp.src(config.src.styles.toolkit)
		.pipe(sass({
			errLogToConsole: true
		}))
		.pipe(prefix('last 1 version'))
		.pipe(gulpif(!isDev, csso()))
		.pipe(gulp.dest(config.dest + '/toolkit/styles'))
		.pipe(gulpif(isDev, reload({stream:true})))


gulp.task('styleguide:styles', ['styleguide:styles:fabricator', 'styleguide:styles:toolkit'])


# -----------------------------------------------------------------------o Scripts

gulp.task 'styleguide:scripts:fabricator', () ->
	console.log config.dest
	gulp.src(config.src.scripts.fabricator)
		.pipe(concat('f.js'))
		.pipe(gulpif(!isDev, uglify()))
		.pipe(gulp.dest(config.dest + '/fabricator/scripts'))


gulp.task 'styleguide:scripts:toolkit', () ->
	browserify(config.src.scripts.toolkit)
		.bundle()
		.on('error', (error) ->
			gutil.log(gutil.colors.red(error));
			this.emit('end');
		)
		.pipe(source('toolkit.js'))
		.pipe(gulpif(!isDev, streamify(uglify())))
		.pipe(gulp.dest(config.dest + '/toolkit/scripts'))


gulp.task('styleguide:scripts', ['styleguide:scripts:fabricator', 'styleguide:scripts:toolkit'])


# -----------------------------------------------------------------------o Scripts

gulp.task 'styleguide:images', ['styleguide:favicon'], () ->
	gulp.src(config.src.images)
		.pipe(imagemin())
		.pipe(gulp.dest(config.dest + '/toolkit/images'))


gulp.task 'styleguide:favicon', () ->
	gulp.src('./src/favicon.ico')
		.pipe(gulp.dest(config.dest))


# -----------------------------------------------------------------------o Collate

gulp.task 'styleguide:collate', () ->

	# 'collate' is a little different -
	# it returns a promise instead of a stream

	deferred = Q.defer()
	opts = {
		materials: config.src.materials,
		dest: config.dest + '/fabricator/data/data.json'
	}

	# run the collate task; resolve deferred when complete

	collate(opts, deferred.resolve)

	return deferred.promise


# -----------------------------------------------------------------------o Assembly

gulp.task 'styleguide:assemble:fabricator', () ->
	opts = {
		data: config.dest + '/fabricator/data/data.json'
		template: false
	}

	gulp.src(config.src.views)
		.pipe(compile(opts))
		.pipe(gulp.dest(config.dest))


gulp.task 'styleguide:assemble:templates', () ->
	opts = {
		data: config.dest + '/fabricator/data/data.json'
		template: true
	}

	gulp.src('./src/toolkit/templates/*.html')
		.pipe(compile(opts))
		.pipe(rename({
			prefix: 'template-'
		}))
		.pipe(gulp.dest(config.dest))


gulp.task 'styleguide:assemble', ['styleguide:collate'], () ->
	gulp.start('styleguide:assemble:fabricator', 'styleguide:assemble:templates')



# -----------------------------------------------------------------------o Server

gulp.task 'styleguide:browser-sync', () ->
	browserSync({
		server: {
			baseDir: config.dest
		},
		notify: false
		logPrefix: 'FABRICATOR'
	})


# -----------------------------------------------------------------------o Watch

gulp.task 'styleguide:watch', ['styleguide:browser-sync'], () ->
	gulp.watch('../src/styleguide/toolkit/{components,structures,templates,documentation,views}/**/*.{html,md}', ['assemble', browserSync.reload])
	gulp.watch('../src/styleguide/fabricator/styles/**/*.scss', ['styles:fabricator'])
	gulp.watch('../src/styleguide/toolkit/assets/styles/**/*.scss', ['styles:toolkit'])
	gulp.watch('../src/styleguide/fabricator/scripts/**/*.js', ['scripts:fabricator', browserSync.reload])
	gulp.watch('../src/styleguide/toolkit/assets/scripts/**/*.js', ['scripts:toolkit', browserSync.reload])
	gulp.watch(config.src.images, ['images', browserSync.reload])



# -----------------------------------------------------------------------o Default

gulp.task 'styleguide', ['styleguide:clean'], () ->

	# define build tasks
	tasks = [
		'styleguide:styles',
		'styleguide:scripts',
		'styleguide:images',
		'styleguide:assemble'
	];

	# run build
	runSequence(tasks, () ->
		if config.dev
			gulp.start('styleguide:watch');
	)


