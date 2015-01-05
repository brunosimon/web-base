class UI_Slider

	@START: 'start'
	@CHANGE: 'change'
	@END: 'end'

	constructor: (options) ->

		{@container, @startValue} = options

		@startValue = @startValue || 0

		@_initX = 0
		@_x = 0
		@_moveX = 0

		@_initSlider()


	_initSlider: () =>

		@container.on(Event.MOUSEDOWN, @_onMouseDown)


	_onMouseDown: (event) =>

		e = if event.type == 'touchstart' then event.originalEvent.touches[0] else event

		@containerWidth = @container.width()
		@_leftSide = @container.offset().left

		@cursorPos = (e.pageX - @_leftSide)
		@perc = @cursorPos / @containerWidth

		$(document)
			.on(Event.MOUSEMOVE, @_onMouseMove)
			.on(Event.MOUSEUP, @_onMouseUp)

		$(@).trigger(UI_Slider.START)


	_onMouseMove: (event) =>

		e = if event.type == 'touchmove' then event.originalEvent.touches[0] else event

		@cursorPos = (e.pageX - @_leftSide)
		@perc = @cursorPos / @containerWidth

		if @perc < 0
			@perc = 0
		else if @perc > 1
			@perc = 1

		$(@).trigger(UI_Slider.CHANGE)

	_onMouseUp: () =>
		
		$(document)
			.off(Event.MOUSEMOVE, @_onMouseMove)
			.off(Event.MOUSEUP, @_onMouseUp)

		$(@).trigger(UI_Slider.END)




