fs 				= require('fs')
path 			= require('path')
Handlebars 		= require('handlebars')
through 		= require('through2')


# --------------------------------------------o
# Contents of data.json
# @type {Object}

data = null

# --------------------------------------------o
# Register partials with Handlbars

registerPartials = () ->

	partials = fs.readdirSync('../src/styleguide/toolkit/views/partials')
	html

	for i in [partials.length - 1..0] by -1
		html = fs.readFileSync('../src/styleguide/toolkit/views/partials/' + partials[i], 'utf-8')
		Handlebars.registerPartial(partials[i].replace(/.html/, ''), html)


assembleFabricator = (file, enc, cb) ->

	# augment data object
	data.fabricator = true

	# template pages

	source = file.contents.toString()
	template = Handlebars.compile(source)
	html = template(data)

	file.contents = new Buffer(html)

	this.push(file)

	cb()


assembleTemplates = () ->

	# augment data object
	data.fabricator = false

	# use the filename as the key value lookup in the data.json object
	key = path.basename(file.path, '.html').replace(/-/g, '')

	comments = {
		start: '\n\n<!-- Start ' + data.templates[key].name + ' template -->\n\n',
		end: '\n\n<!-- /End ' + data.templates[key].name + ' template -->\n\n'
	}

	source = '{{> intro}}' +
				comments.start +
				data.templates[key].content +
				comments.end +
				'{{> outro}}'

	template = Handlebars.compile(source)
	html = template(data)

	file.contents = new Buffer(html)

	@push(file)

	cb()


module.exports = (opts) ->
	data = JSON.parse(fs.readFileSync(opts.data))
	registerPartials()
	return through.obj( if (opts.template) then assembleTemplates else assembleFabricator) 
