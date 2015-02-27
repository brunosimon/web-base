# ---------------------------------------------------------------------------o
# Collate "materials" - html and md files
# @description Gets contents of files, parses, and creates JSON
# ---------------------------------------------------------------------------o

beautifyHtml			= require('js-beautify').html
changeCase 				= require('change-case')
cheerio 				= require('cheerio')
fs 						= require('fs')
gutil 					= require('gulp-util')
Handlebars 				= require('handlebars')
junk 					= require('junk')
markdown 				= require('markdown-it')({ langPrefix: 'language-' })
mkpath 					= require('mkpath')
path 					= require('path')


# --------------------------------------------o
# Compiled component/structure/etc data
# @type {Object}

data = null


# configure beautifier

beautifyOptions = {
	'indent_size': 1
	'indent_char': '    '
	'indent_with_tabs': true
}


# --------------------------------------------o
# Register each component and structure as a helper in Handlebars
# This turns each item into a helper so that we can
# include them in other files.

registerHelper = () ->

	Handlebars.registerHelper(item.id, (item) ->

		# get helper classes if passed in
		helperClasses = if typeof arguments[0] == 'string' then arguments[0] else ''

		# init cheerio
		$ = cheerio.load(item.content)

		# add helper classes to first element
		$('*').first().addClass(helperClasses)

		return new Handlebars.SafeString($.html())

	)



# --------------------------------------------o
# Block iteration
# @description Repeat a block a given amount of times.
# @example
# {{#iterate 20}}
#   <li>List Item {{@index}}</li>
# {{/iterate}}

Handlebars.registerHelper('iterate', (n, block) ->

	accum = ''
	data

	for i in [0...n]
		if block.data
			data = Handlebars.createFrame(block.data or {})
			data.index = i
		accum += block.fn(i, {data: data})

	return accum

)

# --------------------------------------------o
# Parse a directory of files
# @param {Sting} dir The directory that contains .html and .md files to be parsed
# @return {Function} A stream

parse = (dir) ->

	# create key if it doesn't exist
	if !data[dir]
		data[dir] = {}

	raw = fs.readdirSync('../site/dev/App/views/styleguide/' + dir).filter(junk.not)

	fileNames = raw.map (e, i) ->
		return e.replace(path.extname(e), '')

	items = fileNames.filter (e, i, a) ->
		return a.indexOf(e) == i
	
	for i in [0...items.length]
		item = {}

		item.id = items[i]
		item.name = changeCase.titleCase(item.id.replace(/-/ig, ' '))

		try
			# compile template
			content = fs.readFileSync('../site/dev/App/views/styleguide/' + dir + '/' + items[i] + '.html', 'utf8').replace(/(\s*(\r?\n|\r))+$/, '')
			template = Handlebars.compile(content)
			item.content = beautifyHtml(template(), beautifyOptions)

			# register the helper
			registerHelper(item)
		catch e
			null

		try 
			notes = fs.readdirSync('../src/styleguide/toolkit/' + dir + '/' + items[i] + '.md', 'utf8')
			item.notes = markdown.render(notes)
		catch e
			null

		data[dir][item.id.replace(/-/g, '')] = item


module.exports = (opts, cb) ->

	data = {}

	for i in [0...opts.materials.length]
		parse(opts.materials[i])

	mkpath.sync(path.dirname(opts.dest))

	fs.writeFile(opts.dest, JSON.stringify(data), (err) ->
		if err
			gutil.log(err)
		else
			cb()
	)

		








