class Transitions

	@START = 'callstart'
	@END = 'callend'
	@MIDDLE = 'callmiddle'

	constructor: () ->

		@router = new Router()

		@_transitionInDelay = 0
		@transitionsWhenCallFinished = true

		@_initEvents()


	_initEvents: () =>

		$(@router)
			.on(Router.CLICK, @_onRouterClick)
			.on(Router.CALLSTART, @_onRouterStart)
			.on(Router.CALLEND, @_onRouterEnd)


	_onRouterClick: () =>


	_onRouterStart: () =>

		if @transitionsWhenCallFinished != true
			@_transitionOut()


	_onRouterEnd: () =>

		if @transitionsWhenCallFinished == true

			transitionName = '_' + @router.pages.prev + 'To' + @router.pages.current.charAt(0).toUpperCase() + @router.pages.current.slice(1)

			if @[transitionName]
				@[transitionName]()
			else
				@_transitionOut()
				setTimeout () =>
					@_transitionIn()
				, @_transitionInDelay
		else
			@_transitionIn()


	_transitionOut: () =>

		transitionName = '_' + @router.pages.prev + 'Out'

		if @[transitionName]
			@[transitionName]()
		else
			@_defaultOut()


	_transitionIn: () =>

		transitionName = '_' + @router.pages.current + 'In'

		$(window).scrollTop(0)

		if @[transitionName]
			@[transitionName]()
		else
			@_defaultIn()


	_defaultOut: () =>
		
		@container = $('.ajaxContainer')
		@router.requestInProgress = true
		@container.addClass('removed')
		@container[0].offsetHeight

		$(@).trigger(Transitions.START)


	_defaultIn: () =>

		oldContainer = $('.ajaxContainer')
		newContainer = @router.content
		
		oldContainer.eq(0).after(newContainer)
		oldContainer.remove()

		newContainer.addClass('added')
		newContainer[0].offsetHeight
		newContainer.removeClass('added')

		@sectionId = @router.pages.current

		$(@).trigger(Transitions.MIDDLE)

		@router.requestInProgress = false

		$(@).trigger(Transitions.END)
















