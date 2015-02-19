class AppCore

	constructor: () ->

		console.log '%c# --------------------o Running Desktop', 'background: #42e34d; color: #0F0F0F;'

		W.init()

		@_initContent()
		@_initEvents()

		@_onResize()



	# -----------------------------------------------------------------------------o private

	_initContent: () =>

		W.time = {
			now: +new Date()
			old: +new Date()
		}

		@transitions = new Transitions()

		@_initPage()




	_initPage: () =>

		@_destroySection()

		@pageId = Router.singleton.pages.current.replace('part-','')

		console.log @pageId

		if @pageId
			Class = App.pages[@pageId] || Page
			@section = new Class({
				pageId: @pageId
			})

		@_onResize()


	_destroySection: () =>

		if @section
			@section.destroy()

		@section = undefined


	_initEvents: () =>

		W.window
			.on('resize', @_onResize)

		$(@transitions)
			.on(Transitions.START, @_onTransitionsStart)
			.on(Transitions.MIDDLE, @_onTransitionsMiddle)
			.on(Transitions.END, @_onTransitionsEnd)




	# -----------------------------------------------------------------------------o listeners

	_onResize: () =>

		W.sw = screen.width
		W.sh = screen.height
		W.ww = W.window.width()
		W.wh = W.window.height()

		if @section && @section.resize
			@section.resize()


	_onTransitionsStart: () =>



	_onTransitionsMiddle: () =>

		@_initPage()


	_onTransitionsEnd: () =>





	


	# -----------------------------------------------------------------------------o public

	update: () =>

		W.time.now = +new Date()
		W.time.delta = (W.time.now - W.time.old) / 1000
		W.time.old = W.time.now

		if @section && @section.update
			@section.update()


$ ->
	
	app = new App()

	(tick = () ->
		app.update()
		window.requestAnimationFrame(tick)
	)()