$ 				= require 'jquery'
Loader 			= require 'libs/loader/Loader'

module.exports = class Page

	constructor: (options) ->

		{@pageId} = options

		console.log '%c# --------------------o Initialize Class ' + @pageId, 'background: #e1e342; color: #0F0F0F;'

		@_initContent()
		@_initEvents()


	# -----------------------------------------------------------------------------o private

	_initContent: () =>
			
		@container = $('#part-' + @pageId.charAt(0).toLowerCase() + @pageId.slice(1) )

		new Loader({
			container: @container
		})

		

	_initEvents: () =>



	# -----------------------------------------------------------------------------o listeners





	# -----------------------------------------------------------------------------o public

	resize: () =>



	update: () =>



	destroy: () =>

		name = @.constructor.name

		console.log '%c# --------------------o Destroy Class ' + name, 'background: #e3b042; color: #0F0F0F;'




