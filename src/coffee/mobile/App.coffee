class App

	constructor: () ->

		console.log '%c# --------------------o Running Desktop', 'background: #42e34d; color: #F0F0F0;'

		W.init()

		@_initEvents()

		@_onResize()



	# -----------------------------------------------------------------------------o private

	_initEvents: () =>

		W.window.on('resize', @_onResize)


	


	# -----------------------------------------------------------------------------o listeners

	_onKeyDown: (e) =>


	_onResize: () =>

		W.sw = screen.width
		W.sh = screen.height
		W.ww = W.window.width()
		W.wh = W.window.height()

		W.body.css {
			'height': W.wh
			'width': W.ww
		}


	


	# -----------------------------------------------------------------------------o public

	update: () =>




$ ->
	
	app = new App()

	(tick = () ->
		app.update()
		window.requestAnimationFrame(tick)
	)()