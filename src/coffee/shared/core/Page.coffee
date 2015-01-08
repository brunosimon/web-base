class Page

	constructor: () ->

		name = @.constructor.name

		console.log '%c# --------------------o Initialize Class ' + name, 'background: #e1e342; color: #0F0F0F;'

		@_initContent()
		@_initEvents()


	# -----------------------------------------------------------------------------o private

	_initContent: () =>
		
		@container = $('#part-' + name.charAt(0).toLowerCase() + name.slice(1) )

		

	_initEvents: () =>



	# -----------------------------------------------------------------------------o listeners





	# -----------------------------------------------------------------------------o public

	resize: () =>



	update: () =>



	destroy: () =>

		name = @.constructor.name

		console.log '%c# --------------------o Destroy Class ' + name, 'background: #e3b042; color: #0F0F0F;'




