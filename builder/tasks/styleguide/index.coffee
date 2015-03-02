gulp 					= require 'gulp'
fs 						= require 'fs'
junk 					= require 'junk'



gulp.task 'styl', () =>

	# ------------------------------------------------o get devices

	devices = fs.readdirSync( '../site/dev/App/views' ).filter( junk.not ).filter( (elm) => return elm != 'styleguide' )


	# ------------------------------------------------o get components, structures and everything

	components = []
	for i in [0...devices.length]

		device = devices[i]
		dirs = fs.readdirSync( '../site/dev/App/views/' + device ).filter( junk.not ).filter( (elm) => return elm in ['components', 'modules', 'templates'])
		output = {}

		output.device = device
		output.components = []

		# extract parts (components, modules, templates) for each device
		for j in [0...dirs.length]
			item = {}
			item.dir = dirs[j]
			item.files = []

			files = fs.readdirSync( '../site/dev/App/views/' + device + '/' + item.dir ).filter( junk.not )

			# extract component for each part
			for k in [0...files.length]
				file = {}
				file.name = files[k].replace( '.html.twig', '' )
					
				# extract the content and the comment in the component
				content = fs.readFileSync( '../site/dev/App/views/' + device + '/' + item.dir + '/' + file.name + '.html.twig', 'utf8' )
				content = content.split('#}')

				file.content = content[content.length - 1].replace(/[\n]+/, '')
				file.comment = {}
				
				# extract the comment
				if file.length > 1
					header = content[0].replace('{##', '').split('#').splice(1)

					for l in [0...header.length]
						comment = header[l].replace(/[ ]+@/, '')
						commentName = comment.substr(0, comment.indexOf(' '))
						commentContent = comment.substr(comment.indexOf(' ')+1)

						file.comment[commentName] = commentContent

				item.files.push(file)

			output.components.push(item)
		
		components.push(output)

	#for i in [0...files.length]


	# ------------------------------------------------o get scss variables
	
	scssFile = fs.readFileSync( '../src/scss/shared/variables.scss', 'utf8' )
	sections = scssFile.replace(/(\r\n|\n|\r|\t)/gm,"").split('/**').splice(1)

	variables = []

	for i in [0...sections.length]
		section = sections[i]
		part = {}

		parts = section.split('*/')
		header = parts[0].split('*').splice(1)
		content = parts[1].replace(/\s/gm, '').split(/[:;]/).filter((elm) => return elm != '')

		for j in [0...header.length]
			comment = header[j].split(' ').splice(1)
			partName = comment[0].substr(1)

			comment = comment.splice(1).join(' ')
			part[partName] = comment

		part.content = []

		for k in [0...content.length] by 2
			part.content.push([content[k], content[k + 1]])

		variables.push(part)

	# ------------------------------------------------o add content to data twig template

	content = '{% set components = ' + JSON.stringify(components) + ' %}'
	content += '{% set colors = ' + JSON.stringify(variables) + ' %}'
	content += '{% block layout %}{% endblock %}' 					# needed to set `data` as a global variable
	fs.writeFile('../site/dev/App/views/styleguide/partials/data.html.twig', content)

		#console.log header

	#console.log sections

	###
	colorsContent = scssFile.split('// --> Colors start')[1].split('// --> Colors end')[0]
	comments = colorsContent.match(/\/\/ @part(.*)\n/gm)
	colorsList = colorsContent.replace(/\/\/ @part(.*)\n/gm, '').replace(/\s/gm, '').split(').filter((elm) => return elm != '')

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
	###


	###
	###