class Scroll

	@DOWN: 'scroll_active'
	@UP: 'scroll_inactive'

	constructor: (options) ->

		{@container} = options

		@viewport = @container.find('.scroll-viewport')
		@overview = @container.find('.scroll-overview')

		@scrollbarContainer = @container.find('.scrollbar-container')
		@scrollbarTrack = @scrollbarContainer.children('.scrollbar-track')
		@scrollbarThumb = @scrollbarTrack.children('.scrollbar-thumb')

		@scrollId = '#' + @container.attr('id')

		if @container.attr('class')
			@scrollId += '.' + @container.attr('class').split(' ').join('.')

		@scrollTop = {
			real: 0
			calc: 0
			limit: 0
			perc: 0
			prev: 0
		}

		@scrollbar = {
			real: 0
			calc: 0
			perc: 0
			trackHeight: @scrollbarTrack.height()
			thumbHeight: @scrollbarThumb.outerHeight()
		}

		@overviewHeight = 1
		@scale = 1

		@isScrollbar = false
		@isMouseMoving = false
		@scrollEnd = false

		@ease = @initEase = 0.9
		@i = 0
		@prevY = @moveY = @initY = 0

		@resize()

		@_initEvents()


	_initEvents: () =>

		@container
			.on(Event.MOUSEDOWN, @_onMouseDown)
			.on(Event.WHEEL, @_onMouseWheel)


	_onMouseDown: (event) =>

		e = if event.type == 'touchstart' then event.originalEvent.touches[0] else event

		if event.which in [0, 1]
			target = $(e.target)
			klass = target.attr('class') || ''

			@scrollbarContainer.addClass('active')

			if target.parents(@scrollId).length
				if klass.match('scrollbar') || target.parents('.scrollbar-thumb').length == 1
					@isScrollbar = true
					@initScrollY = (@scrollTop.perc * (@scrollbar.trackHeight - @scrollbar.thumbHeight))
					@initY = e.pageY - @initScrollY
				else 
					@isScrollbar = false
					@ease = 1
					@initScrollY = @scrollTop.perc * @scrollTop.limit
					@initY = e.pageY

				$(@).trigger(Scroll.DOWN)

				W.body
					.on(Event.MOUSEMOVE, @_onMouseMove)
					.on(Event.MOUSEUP, @_onMouseUp)

			event.preventDefault()


	_onMouseMove: (event) =>

		e = if event.type == 'touchmove' then event.originalEvent.touches[0] else event
		
		if event.which in [0, 1]
			@isMouseMoving = true
			if @isScrollbar == true
				@moveY = e.pageY - @initY
				@scrollTop.perc = @moveY / (@scrollbar.trackHeight - @scrollbar.thumbHeight)
			else
				@prevY = @moveY
				@moveY = - @initScrollY + e.pageY - @initY
				@scrollTop.perc = - @moveY / @scrollTop.limit

			event.preventDefault()


	_onMouseUp: () =>

		if @isMouseMoving == true && @isScrollbar == false
			@scrollTop.perc = -(@moveY + (@moveY - @prevY) * 10) / @scrollTop.limit

		@resetEase()
		@isScrollbar = @isMouseMoving = false

		$(@).trigger(Scroll.UP)

		W.body
			.off(Event.MOUSEMOVE, @_onMouseMove)
			.off(Event.MOUSEUP, @_onMouseUp)


		@scrollbarContainer.removeClass('active')




	_onMouseWheel: (e, deltaY) =>

		target = $(e.target)

		if target.parents(@scrollId).length
			@resetEase()
			#@scrollTop.perc += deltaY / 20000
			initScrollY = @scrollTop.perc * @scrollTop.limit
			moveY = - initScrollY + deltaY
			@scrollTop.perc = - moveY / @scrollTop.limit
			
			e.preventDefault()


	keyDown: (e) =>

		###
			32: space
			40: down
			38: up
			16: shift
			91: cmd
			18: alt
		###

		initScrollY = @scrollTop.perc * @scrollTop.limit

		moveY = - initScrollY
		switch (e.keyCode)
			when 32 then moveY -= @container.height()
			when 40 then moveY -= 40
			when 38 then moveY += 40 

		@scrollTop.perc = - moveY / @scrollTop.limit


	goTo: (pos, changeEase) =>

		if changeEase
			@ease = 0.1
		@scrollTop.perc = pos / @scrollTop.limit


	goTop: () =>

		@ease = 0.1
		@scrollTop.perc = 0
		

	refresh: () =>

		@viewport = @container.find('.scroll-viewport')
		@overview = @container.find('.scroll-overview')

		@scrollbarContainer = @container.find('.scrollbar-container')
		@scrollbarTrack = @scrollbarContainer.children('.scrollbar-track')
		@scrollbarThumb = @scrollbarTrack.children('.scrollbar-thumb')

		@scrollTop.perc = 0

		@resetEase()
		@resize()


	resetEase: () =>

		@ease = @initEase

	setScale: (val) =>

		@scale = val


	resize: () ->

		@viewportHeight = @viewport.outerHeight()
		@overviewHeight = @overview.outerHeight()
		@scrollTop.limit = @overviewHeight - @viewportHeight

		#@scrollbarContainer.height(@container.height())
		@scrollbar.trackHeight = @scrollbarTrack.height()

		if @scrollbar.thumbHeight == 0 || @scrollbar.autoresize == true
			@scrollbar.thumbHeight = Math.max(@scrollbar.trackHeight / (@scrollTop.limit / @viewportHeight), 50)
			@scrollbar.autoresize = true

			@scrollbarThumb.css {
				'height': @scrollbar.thumbHeight
			}


	update: () ->

		if @scrollTop.perc < 0
			@scrollTop.perc = 0
		else if @scrollTop.perc > 1
			@scrollTop.perc = 1

		@scrollTop.prev = @scrollTop.calc
		@scrollTop.real = @scrollTop.perc * @scrollTop.limit
		@scrollTop.calc += (@scrollTop.real - @scrollTop.calc) * @ease

		if ~~ @scrollTop.calc == 0
			@scrollTop.calc = 0

		#if ~~ @scrollTop.prev != ~~ @scrollTop.calc || 1

		if ~~ @scrollTop.prev != ~~ @scrollTop.calc
			Normalize.transform(@overview[0], 'translate3d(0, ' +  (-@scrollTop.calc * @scale) + 'px, 0)')
			Normalize.transform(@scrollbarThumb[0], 'translate3d(0, ' + ((@scrollTop.real / @scrollTop.limit) * (@scrollbar.trackHeight - @scrollbar.thumbHeight)) + 'px, 0)')
			
			if @scrollEnd == false
				@scrollEnd = true
				@scrollbarContainer.addClass('active')
		else
			if @scrollEnd == true
				@scrollEnd = false
				@scrollbarContainer.removeClass('active')

		#if @ease != @initEase 
		#	@resetEase()





