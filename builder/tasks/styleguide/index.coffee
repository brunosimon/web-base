gulp 					= require 'gulp'
fs 						= require 'fs'
junk 					= require 'junk'



gulp.task 'styl', () =>

	# ------------------------------------------------o get components, structures and everything

	dirs = fs.readdirSync( '../site/dev/App/views/shared' ).filter( junk.not )
	components = []

	for i in [0...dirs.length]
		item = {}
		item.dir = dirs[i]
		item.files = []

		files = fs.readdirSync( '../site/dev/App/views/shared/' + item.dir ).filter( junk.not )

		for j in [0...files.length]
			file = {}
			file.name = files[j].replace( '.html.twig', '' )
			file.content = fs.readFileSync( '../site/dev/App/views/shared/' + item.dir + '/' + file.name + '.html.twig', 'utf8' )
			item.files.push(file)

		components.push(item)

	#for i in [0...files.length]


	# ------------------------------------------------o get scss variables
	
	scssFile = fs.readFileSync( '../src/scss/shared/variables.scss', 'utf8' )

	colorsContent = scssFile.split('// --> Colors start')[1].split('// --> Colors end')[0]
	comments = colorsContent.match(/\/\/ @part(.*)\n/gm)
	colorsList = colorsContent.replace(/\/\/ @part(.*)\n/gm, '###').replace(/\s/gm, '').split('###').filter((elm) => return elm != '')

	colors = []

	for i in [0...comments.length]
		part = {}
		part.name = comments[i].replace('// @part ','').replace('\n', '')
		part.colors = []

		partColors = colorsList[i].split(/[;:]/).filter((elm) => return elm != '')

		for j in [0...partColors.length] by 2
			color = {}
			color.name = partColors[j]
			color.val = partColors[j + 1]
			part.colors.push(color)

		colors.push(part)
	

	console.log colors



	content = '{% set components = ' + JSON.stringify(components) + ' %}'
	content = '{% set colors = ' + JSON.stringify(colorsContent) + ' %}'
	content += '{% block layout %}{% endblock %}' 					# needed to set `data` as a global variable
	fs.writeFile('../site/dev/App/views/styleguide/partials/data.html.twig', content)